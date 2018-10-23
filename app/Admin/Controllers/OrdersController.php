<?php

namespace App\Admin\Controllers;

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
     * Show interface.
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('订单管理')
            ->description('商品订单 - 详情')
            ->body($this->detail($id));
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

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->id('Id');
        $grid->order_sn('Order sn');
        $grid->user_id('User id');
        $grid->user_info('User info');
        $grid->status('Status');
        $grid->currency('Currency');
        $grid->payment_method('Payment method');
        $grid->payment_sn('Payment sn');
        $grid->paid_at('Paid at');
        $grid->closed_at('Closed at');
        $grid->shipment_sn('Shipment sn');
        $grid->shipment_company('Shipment company');
        $grid->shipped_at('Shipped at');
        $grid->completed_at('Completed at');
        $grid->commented_at('Commented at');
        $grid->snapshot('Snapshot');
        $grid->total_shipping_fee('Total shipping fee');
        $grid->total_amount('Total amount');
        $grid->remark('Remark');
        $grid->deleted_at('Deleted at');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
