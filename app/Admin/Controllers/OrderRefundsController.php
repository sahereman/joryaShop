<?php

namespace App\Admin\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\PaymentsController;
use App\Http\Requests\Request;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yansongda\Pay\Pay;
use PayPal\Api\Payment;
use PayPal\Api\RefundRequest;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;

class OrderRefundsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('订单管理')
            ->description('售后订单 - 列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     * @param OrderRefund $refund
     * @param Content $content
     * @return Content
     */
    public function show(OrderRefund $refund, Content $content)
    {

        return $content
            ->header('订单管理')
            ->description('售后订单 - 详情')
            ->body(view('admin.order_refunds.show', [
                'refund' => $refund,
                'order' => $refund->order,
            ]));
    }

    /**
     * @param OrderRefund $refund
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function check(OrderRefund $refund, Request $request)
    {
        if ($refund->status == OrderRefund::ORDER_REFUND_STATUS_REFUNDED)
        {
            return response()->json([
                'messages' => '订单已退款,不可重复退款'
            ], 422);
        } else if ($refund->status == OrderRefund::ORDER_REFUND_STATUS_DECLINED)
        {
            return response()->json([
                'messages' => '订单退款已被拒绝'
            ], 422);
        }

        if ($refund->type == OrderRefund::ORDER_REFUND_TYPE_REFUND) // 仅退款
        {

            try
            {
                $response = DB::transaction(function () use ($refund) {

                    $order = Order::where('id', $refund->order_id)->lockForUpdate()->first();

                    if ($order == null) throw new \Exception('lockForUpdate');

                    if ($this->refundMoney($order) === true)
                    {
                        return response()->json([
                            'messages' => '退款成功'
                        ], 200);
                    } else
                    {
                        return response()->json([
                            'messages' => '系统错误'
                        ], 500);
                    }
                });

                return $response;
            } catch (\Exception $e)
            {
                return response()->json([
                    'messages' => '网络繁忙'
                ], 429);
            }


        } elseif ($refund->type == OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT) // 退货并退款
        {
            try
            {
                $response = DB::transaction(function () use ($refund, $request) {

                    $order = Order::where('id', $refund->order_id)->lockForUpdate()->first();

                    if ($order == null)
                    {
                        throw new \Exception('lockForUpdate');
                    }

                    // 将退款订单的状态标记为shipping(待发货)并保存审核时间
                    $order->refund->update([
                        'status' => OrderRefund::ORDER_REFUND_STATUS_SHIPPING,
                        'checked_at' => now(), // 审核时间
                        'seller_info' => ['name' => $request->name, 'phone' => $request->phone, 'address' => $request->address]
                    ]);

                    return response()->json([
                        'messages' => '审核通过并提醒买家发货'
                    ], 200);

                });

                return $response;
            } catch (\Exception $e)
            {
                return response()->json([
                    'messages' => '网络繁忙'
                ], 429);
            }

        } else
        {
            throw new InvalidRequestException('退款状态不符合');
        }

    }

    /**
     * @param OrderRefund $refund
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function receive(OrderRefund $refund)
    {
        if ($refund->type == OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT && $refund->status == OrderRefund::ORDER_REFUND_STATUS_RECEIVING)
        {

            try
            {
                $response = DB::transaction(function () use ($refund) {

                    $order = Order::where('id', $refund->order_id)->lockForUpdate()->first();

                    if ($order == null) throw new \Exception('lockForUpdate');

                    if ($this->refundMoney($order) === true)
                    {
                        return response()->json([
                            'messages' => '退款成功'
                        ], 200);
                    } else
                    {
                        return response()->json([
                            'messages' => '系统错误'
                        ], 500);
                    }
                });

                return $response;
            } catch (\Exception $e)
            {
                return response()->json([
                    'messages' => '网络繁忙'
                ], 429);
            }

        } else
        {
            throw new InvalidRequestException('退款状态不符合');
        }

    }


    public function refundMoney($order)
    {
        switch ($order->payment_method)
        {
            case Order::PAYMENT_METHOD_ALIPAY:
                return $this->alipayRefund($order);
                break;
            case Order::PAYMENT_METHOD_WECHAT:
                return $this->wechatRefund($order);
                break;
            case Order::PAYMENT_METHOD_PAYPAL:
                return $this->paypalRefund($order);
                break;
            default:
                return false;
                break;
        }
    }

    public function refundSuccessHandle($order)
    {

        // 将退款订单的状态标记为退款成功并保存退款時間
        $order->refund->update([
            'status' => OrderRefund::ORDER_REFUND_STATUS_REFUNDED,
            'checked_at' => now(), // 审核时间
            'refunded_at' => now(), // 退款时间
        ]);

        return true;
    }


    public function alipayRefund($order)
    {

        Log::info('A New Alipay Refund Begins: order refund id - ' . $order->refund->id);

        try
        {
            // 调用支付宝支付实例的 refund 方法
            $response = Pay::alipay(PaymentsController::getAlipayConfig($order))->refund([
                'out_trade_no' => $order->order_sn, // 之前的订单流水号
                'refund_amount' => bcadd($order->total_amount, $order->total_shipping_fee, 2), // 退款金额，单位元
            ]);

            Log::info('A New Alipay Refund Responded: ' . $response->toJson());

            // 根据支付宝的文档，如果返回值里有 sub_code 字段说明退款失败
            if ($response->sub_code || $response->code != 10000 || $response->msg != 'Success')
            {
                Log::error('A New Alipay Refund Failed: ' . json_encode($response));
            }

            $this->refundSuccessHandle($order);


            Log::info('A New Alipay Refund Completed: order refund id - ' . $order->refund->id);

            return true;
        } catch (\Exception $e)
        {
            Log::error('A New Alipay Refund Completed: order refund id - ' . $order->refund->id . '; With Error Message: ' . $e->getMessage());

            return false;
        }
    }


    public function wechatRefund($order)
    {
        Log::info('A New Wechat Refund Begins: order refund id - ' . $order->refund->id);

        try
        {
            // 调用Wechat支付实例的 refund 方法
            $response = Pay::wechat(PaymentsController::getWechatConfig($order))->refund([
                'out_trade_no' => $order->order_sn, // 之前的订单流水号
                'out_refund_no' => $order->refund->refund_sn, // 退款订单流水号
                'total_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 订单金额，单位分，只能为整数
                'refund_fee' => bcmul(bcadd($order->total_amount, $order->total_shipping_fee, 2), 100, 0), // 退款金额，单位分，只能为整数
                'refund_desc' => '这是来自 Jorya Hair 的退款订单' . $order->refund->refund_sn,
            ]);


            Log::info('A New Wechat Refund Responded: ' . $response->toJson());

            // 根据Wechat的文档，如果返回值里有 sub_code 字段说明退款失败
            if ($response->return_code != 'SUCCESS' || $response->result_code != 'SUCCESS')
            {
                Log::error('A New Wechat Refund Failed: ' . json_encode($response));
            }

            $this->refundSuccessHandle($order);

            Log::info('A New Wechat Refund Completed: order refund id - ' . $order->refund->id);

            return true;

        } catch (\Exception $e)
        {
            Log::error('A New Wechat Refund Completed: order refund id - ' . $order->refund->id . '; With Error Message: ' . $e->getMessage());

            return false;
        }
    }


    public function paypalRefund($order)
    {
        Log::info('A New Paypal Refund Begins: order refund id - ' . $order->refund->id);

        $config = PaymentsController::getPaypalConfig($order);
        $oAuthTokenCredential = new OAuthTokenCredential($config[$config['mode']]['client_id'], $config[$config['mode']]['client_secret']);
        $apiContext = new ApiContext($oAuthTokenCredential);
        $apiContext->setConfig($config);
        $restCall = new PayPalRestCall($apiContext);

        $paymentId = $order->payment_sn;
        $payment = Payment::get($paymentId, $apiContext, $restCall);

        $transactions = $payment->getTransactions();
        $amount = $transactions[0]->getAmount();
        $relatedResources = $transactions[0]->getRelatedResources();
        $sale = $relatedResources[0]->getSale();
        // $saleId = $sale->getId();

        /*$payer = $payment->getPayer();
        $payerInfo = $payer->getPayerInfo();
        $payerId = $payerInfo->getPayerId();*/

        $refundRequest = new RefundRequest();
        try
        {
            $detailedRefund = $sale->refundSale($refundRequest, $apiContext, $restCall);
            Log::info("A New Paypal Payment Refund Created: " . $detailedRefund->toJSON());

            /*$state = $detailedRefund->getState();
            $refundId = $detailedRefund->getId();
            $refundToPayer = $detailedRefund->getRefundToPayer();*/

            if ($detailedRefund->getState() != 'completed')
            {
                Log::error('A New Paypal Refund Failed: order refund id - ' . $order->refund->id);
            }

            $this->refundSuccessHandle($order);

            Log::info('A New Paypal Refund Completed: order refund id - ' . $order->refund->id);

            return true;
        } catch (\Exception $e)
        {
            Log::error("A New Paypal Pc Payment Refund Failed: order refund id - " . $order->refund->id . '; With Error Message: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderRefund);
        $grid->model()->with('order')->orderBy('created_at', 'desc'); // 设置初始排序条件
        $grid->disableCreateButton();
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        /*筛选*/
        $grid->filter(function ($filter) {
            $filter->disableIdFilter(); // 去掉默认的id过滤器

            $filter->equal('status', '售后状态')->select(OrderRefund::$orderRefundStatusMap);
        });

        $grid->column('order.order_sn', '订单号');
        $grid->column('order.status', '订单状态')->display(function ($value) {
            return Order::$orderStatusMap[$value] ?? '未知';
        });
        $grid->column('order.currency', '支付币种');
        $grid->column('order.total_shipping_fee', '运费');
        $grid->column('order.total_amount', '金额');

        $grid->type('售后类型')->display(function ($value) {
            return OrderRefund::$orderRefundTypeMap[$value] ?? '未知';
        });
        $grid->status('售后状态')->display(function ($value) {
            return OrderRefund::$orderRefundStatusMap[$value] ?? '未知';
        });

        $grid->column('order.created_at', '下单时间')->sortable();
        $grid->created_at('发起售后时间')->sortable();

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();

            $actions->append('<a class="btn btn-xs btn-warning" style="margin-right:8px" href="' . route('admin.order_refunds.show', [$actions->getKey()]) . '">查看</a>');
        });

        return $grid;
    }

}
