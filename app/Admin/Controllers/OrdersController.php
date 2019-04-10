<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Ajax\Ajax_Delete;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Request;
use App\Models\Config;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Jobs\AutoCompleteOrderJob;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class OrdersController extends Controller
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
            ->description('商品订单 - 列表')
            ->body($this->grid());
    }

    /**
     * @param Order $order
     * @param Content $content
     * @return Content
     */
    public function show($order, Content $content)
    {
        $order = Order::withTrashed()->find($order);
        return $content
            ->header('订单管理')
            ->description('商品订单 - 详情')
            ->body(view('admin.orders.show', ['order' => $order]));

    }

    /**
     * Edit interface.
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('订单管理')
            ->description('商品订单 - 编辑')
            ->body($this->form()->edit($id));
    }

    //修改订单运费
    public function modify(Order $order, Request $request)
    {
        // 判断当前订单是否已支付
        if ($order->paid_at)
        {
            throw new InvalidRequestException('该订单已付款');
        }
        // 判断当前订单发货状态是否为待发货
        if ($order->status !== Order::ORDER_STATUS_PAYING)
        {
            throw new InvalidRequestException('该订单已付款');
        }

        // 验证
        $data = $this->validate($request, [
            'total_shipping_fee' => 'required|numeric|min:0',
        ], [], [
            'total_shipping_fee' => '运费',
        ]);

        // 将订单发货状态改为已发货，并存入物流信息
        $order->update([
            'total_shipping_fee' => $data['total_shipping_fee'],
        ]);

        // 返回上一页
        return redirect()->back();
    }

    //发货
    public function ship(Order $order, Request $request)
    {
        // 判断当前订单是否已支付
        if (!$order->paid_at)
        {
            throw new InvalidRequestException('该订单未付款');
        }
        // 判断当前订单发货状态是否为待发货
        if ($order->status !== Order::ORDER_STATUS_SHIPPING)
        {
            throw new InvalidRequestException('该订单已发货');
        }

        // 验证
        $data = $this->validate($request, [
            'shipment_company' => [
                'required',
                Rule::exists('shipment_companies', 'code')
            ],
            'shipment_sn' => ['required'],
        ], [], [
            'shipment_company' => '物流公司',
            'shipment_sn' => '物流单号',
        ]);

        // 将订单发货状态改为已发货，并存入物流信息
        $order->update([
            'status' => Order::ORDER_STATUS_RECEIVING,
            'shipment_company' => $data['shipment_company'],
            'shipment_sn' => $data['shipment_sn'],
            'shipped_at' => now(),
            'to_be_completed_at' => Carbon::now()->addSeconds(Order::getSecondsToCompleteOrder())
        ]);

        // 分派定时自动关闭订单任务
        $this->dispatch(new AutoCompleteOrderJob($order, Order::getSecondsToCompleteOrder()));

        // 返回上一页
        return redirect()->back();
    }

    //删除
    public function delete(Order $order, Request $request)
    {
        // 判断当前订单状态 必须是 交易关闭 或 已完成
        if (!in_array($order->status, [Order::ORDER_STATUS_CLOSED, Order::ORDER_STATUS_COMPLETED]))
        {
            throw new InvalidRequestException('该订单当前状态不允许删除');
        }

        Order::find($order->id)->delete();

        // 返回上一页
        return response()->json([
            'messages' => '订单删除成功'
        ], 200);
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);
        $grid->model()->with('refund')->orderBy('created_at', 'desc'); // 设置初始排序条件
        $grid->disableCreateButton();
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        /*筛选*/
        $grid->filter(function ($filter) {
            $filter->disableIdFilter(); // 去掉默认的id过滤器

            $filter->equal('status', '订单状态')->select(Order::$orderStatusMap);
            $filter->equal('currency', '支付币种')->select([
                'USD' => '美元',
                'CNY' => '人民币',
            ]);
            $filter->like('order_sn', '订单号');
            $filter->where(function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('email', 'like', "%{$this->input}%");
                    $query->orWhere('phone', 'like', "%{$this->input}%");

                });
            }, '买家(邮箱、客户电话)');
        });

        $grid->id('ID');

        $grid->order_sn('订单号');
        $grid->column('user.email', '邮箱');
        $grid->user_info('收货人姓名')->display(function ($value) {
            return $value['name'];
        });

        $grid->column('', '订单总价')->display(function () {
            return bcadd($this->total_amount, $this->total_shipping_fee, 2);
        });
        $grid->payment_method('支付方式')->display(function ($value) {
            return Order::$paymentMethodMap[$value] ?? '';
        });
        $grid->status('订单状态')->display(function ($value) {
            return Order::$orderStatusMap[$value] ?? '未知';
        });

        $grid->created_at('订单日期')->sortable();

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
            $actions->append('<a class="btn btn-xs btn-primary" style="margin-right:8px" href="' . route('admin.orders.show', [$actions->getKey()]) . '">查看</a>');

            if ($actions->row->refund)
            {
                $actions->append('<a class="btn btn-xs btn-warning" style="margin-right:8px" href="' . route('admin.order_refunds.show', [$actions->row->refund['id']]) . '">售后</a>');
            }

            if (in_array($actions->row->status, [Order::ORDER_STATUS_CLOSED, Order::ORDER_STATUS_COMPLETED]))// 可以删除的订单
            {
                $actions->append(new Ajax_Delete(route('admin.orders.delete', [$actions->getKey()])));
            }
        });

        return $grid;
    }
}
