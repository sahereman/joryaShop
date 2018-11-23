<?php

namespace App\Admin\Controllers;

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
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('订单管理')
            ->description('售后订单 - 详情')
            ->body($this->detail($id));
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

        //        $grid->id('Id');
        //        $grid->order_id('Order id');
        //        $grid->seller_info('Seller info');
        //        $grid->remark_from_user('Remark from user');
        //        $grid->remark_from_seller('Remark from seller');
        //        $grid->remark_for_shipment_from_user('Remark for shipment from user');
        //        $grid->remark_for_shipment_from_seller('Remark for shipment from seller');
        //        $grid->shipment_sn('Shipment sn');
        //        $grid->shipment_company('Shipment company');
        //        $grid->photos_for_refund('Photos for refund');
        //        $grid->photos_for_shipment('Photos for shipment');
        //        $grid->refunded_at('Refunded at');
        //        $grid->created_at('Created at');
        //        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(OrderRefund::findOrFail($id));

        $show->id('Id');
        $show->order_id('Order id');
        $show->seller_info('Seller info');
        $show->type('Type');
        $show->remark_from_user('Remark from user');
        $show->remark_from_seller('Remark from seller');
        $show->remark_for_shipment_from_user('Remark for shipment from user');
        $show->remark_for_shipment_from_seller('Remark for shipment from seller');
        $show->shipment_sn('Shipment sn');
        $show->shipment_company('Shipment company');
        $show->photos_for_refund('Photos for refund');
        $show->photos_for_shipment('Photos for shipment');
        $show->refunded_at('Refunded at');
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
        $form = new Form(new OrderRefund);

        $form->number('order_id', 'Order id');
        $form->text('seller_info', 'Seller info');
        $form->text('type', 'Type');
        $form->text('remark_from_user', 'Remark from user');
        $form->text('remark_from_seller', 'Remark from seller');
        $form->text('remark_for_shipment_from_user', 'Remark for shipment from user');
        $form->text('remark_for_shipment_from_seller', 'Remark for shipment from seller');
        $form->text('shipment_sn', 'Shipment sn');
        $form->text('shipment_company', 'Shipment company');
        $form->text('photos_for_refund', 'Photos for refund');
        $form->text('photos_for_shipment', 'Photos for shipment');
        $form->datetime('refunded_at', 'Refunded at')->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
