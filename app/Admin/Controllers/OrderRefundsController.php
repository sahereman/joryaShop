<?php

namespace App\Admin\Controllers;

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
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderRefund);

        $grid->id('Id');
        $grid->order_id('Order id');
        $grid->seller_info('Seller info');
        $grid->type('Type');
        $grid->remark_by_user('Remark by user');
        $grid->remark_by_seller('Remark by seller');
        $grid->remark_by_shipment('Remark by shipment');
        $grid->shipment_sn('Shipment sn');
        $grid->shipment_company('Shipment company');
        $grid->photos_for_refund('Photos for refund');
        $grid->photos_for_shipment('Photos for shipment');
        $grid->refunded_at('Refunded at');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(OrderRefund::findOrFail($id));

        $show->id('Id');
        $show->order_id('Order id');
        $show->seller_info('Seller info');
        $show->type('Type');
        $show->remark_by_user('Remark by user');
        $show->remark_by_seller('Remark by seller');
        $show->remark_by_shipment('Remark by shipment');
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
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OrderRefund);

        $form->number('order_id', 'Order id');
        $form->text('seller_info', 'Seller info');
        $form->text('type', 'Type');
        $form->text('remark_by_user', 'Remark by user');
        $form->text('remark_by_seller', 'Remark by seller');
        $form->text('remark_by_shipment', 'Remark by shipment');
        $form->text('shipment_sn', 'Shipment sn');
        $form->text('shipment_company', 'Shipment company');
        $form->text('photos_for_refund', 'Photos for refund');
        $form->text('photos_for_shipment', 'Photos for shipment');
        $form->datetime('refunded_at', 'Refunded at')->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
