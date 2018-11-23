<?php

namespace App\Admin\Controllers;

use App\Http\Requests\Request;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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

    public function check(OrderRefund $refund, Request $request)
    {
//        // 判断当前订单是否已支付
//        if (!$order->paid_at)
//        {
//            throw new InvalidRequestException('该订单未付款');
//        }
//        // 判断当前订单发货状态是否为待发货
//        if ($order->status !== Order::ORDER_STATUS_SHIPPING)
//        {
//            throw new InvalidRequestException('该订单已发货');
//        }

        // 验证
//        $data = $this->validate($request, [
//            'shipment_company' => [
//                'required',
//                Rule::exists('shipment_companies', 'code')
//            ],
//            'shipment_sn' => ['required'],
//        ], [], [
//            'shipment_company' => '物流公司',
//            'shipment_sn' => '物流单号',
//        ]);

        // 将订单发货状态改为已发货，并存入物流信息
//        $order->update([
//            'status' => Order::ORDER_STATUS_RECEIVING,
//            'shipment_company' => $data['shipment_company'],
//            'shipment_sn' => $data['shipment_sn'],
//            'to_be_completed_at' => Carbon::now()->addSeconds(Order::getSecondsToCompleteOrder())
//        ]);

//        // 分派定时自动关闭订单任务
//        $this->dispatch(new AutoCompleteOrderJob($order, Order::getSecondsToCompleteOrder()));

        // 返回上一页
        return redirect()->back();

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
