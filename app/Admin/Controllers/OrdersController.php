<?php

namespace App\Admin\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Request;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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
    public function show(Order $order, Content $content)
    {
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
            'shipment_company' => ['required'],
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
        ]);

        // 返回上一页
        return redirect()->back();
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);
        $grid->model()->orderBy('created_at', 'desc'); // 设置初始排序条件
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
            $filter->like('order_sn', '订单号');
            $filter->where(function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('name', 'like', "%{$this->input}%");
                });
            }, '买家(用户名或手机号)');

        });


        $grid->order_sn('订单号');
        $grid->column('user.name', '买家');
        $grid->status('状态')->display(function ($value) {
            return Order::$orderStatusMap[$value] ?? '未知';
        });
        $grid->currency('支付币种');

        $grid->payment_method('支付方式')->display(function ($value) {
            return Order::$paymentMethodMap[$value] ?? '无';
        });
        $grid->total_shipping_fee('运费')->sortable();
        $grid->total_amount('金额')->sortable();
        $grid->created_at('下单时间')->sortable();

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();

            $actions->append('<a class="btn btn-xs btn-primary" style="margin-right:8px" href="' . route('admin.orders.show', [$actions->getKey()]) . '">查看</a>');
            $actions->append('<a class="btn btn-xs btn-warning" style="margin-right:8px" href="' . route('admin.order_refunds.show', [$actions->getKey()]) . '">售后</a>');
            $actions->append('<a class="btn btn-xs btn-danger" style="margin-right:8px" href="' . route('admin.orders.delete', [$actions->getKey()]) . '">删除</a>');
        });

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->id('Id');
        $show->order_sn('Order sn');
        $show->user_id('User id');
        $show->user_info('User info');
        $show->status('Status');
        $show->currency('Currency');
        $show->payment_method('Payment method');
        $show->payment_sn('Payment sn');
        $show->paid_at('Paid at');
        $show->closed_at('Closed at');
        $show->shipment_sn('Shipment sn');
        $show->shipment_company('Shipment company');
        $show->shipped_at('Shipped at');
        $show->completed_at('Completed at');
        $show->commented_at('Commented at');
        $show->snapshot('Snapshot');
        $show->total_shipping_fee('Total shipping fee');
        $show->total_amount('Total amount');
        $show->remark('Remark');
        $show->deleted_at('Deleted at');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->text('order_sn', 'Order sn');
        $form->number('user_id', 'User id');
        $form->text('user_info', 'User info');
        $form->text('status', 'Status')->default('paying');
        $form->text('currency', 'Currency')->default('CNY');
        $form->text('payment_method', 'Payment method');
        $form->text('payment_sn', 'Payment sn');
        $form->datetime('paid_at', 'Paid at')->default(date('Y-m-d H:i:s'));
        $form->datetime('closed_at', 'Closed at')->default(date('Y-m-d H:i:s'));
        $form->text('shipment_sn', 'Shipment sn');
        $form->text('shipment_company', 'Shipment company');
        $form->datetime('shipped_at', 'Shipped at')->default(date('Y-m-d H:i:s'));
        $form->datetime('completed_at', 'Completed at')->default(date('Y-m-d H:i:s'));
        $form->datetime('commented_at', 'Commented at')->default(date('Y-m-d H:i:s'));
        $form->text('snapshot', 'Snapshot');
        $form->decimal('total_shipping_fee', 'Total shipping fee')->default(0.00);
        $form->decimal('total_amount', 'Total amount');
        $form->text('remark', 'Remark');

        return $form;
    }
}
