<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Ajax\Ajax_Delete;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Request;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class OrderRecycleController extends Controller
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
            ->description('订单回收站 - 列表')
            ->body($this->grid());
    }


    //永久删除
    public function delete($order, Request $request)
    {

        $order = Order::withTrashed()->where('id', $order)->where('deleted_at', '!=', null)->first();

        if (!$order)
        {
            throw new InvalidRequestException('该订单当前状态不允许删除');
        }

        // 判断当前订单状态 必须是 交易关闭 或 已完成
        if (!in_array($order->status, [Order::ORDER_STATUS_CLOSED, Order::ORDER_STATUS_COMPLETED]))
        {
            throw new InvalidRequestException('该订单当前状态不允许删除');
        }

        $order->forceDelete();


        // 返回上一页
        return response()->json([
            'messages' => '订单永久删除'
        ], 200);
    }


    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->model()->withTrashed()->where('deleted_at', '!=', null)->with('refund')->orderBy('created_at', 'desc'); // 设置初始排序条件
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

            if (in_array($actions->row->status, [Order::ORDER_STATUS_CLOSED, Order::ORDER_STATUS_COMPLETED]))// 可以删除的订单
            {
                $actions->append(new Ajax_Delete(route('admin.orders_recycle.delete', [$actions->getKey()])));
            }
        });

        return $grid;
    }
}
