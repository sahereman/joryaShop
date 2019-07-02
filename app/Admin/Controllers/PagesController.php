<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;

class PagesController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header($title = '首页')
            ->description('数据统计')
            ->row(function (Row $row) {
                $week = collect([
                    Carbon::today()->toDateString() => 0,
                    Carbon::today()->subDays(1)->toDateString() => 0,
                    Carbon::today()->subDays(2)->toDateString() => 0,
                    Carbon::today()->subDays(3)->toDateString() => 0,
                    Carbon::today()->subDays(4)->toDateString() => 0,
                    Carbon::today()->subDays(5)->toDateString() => 0,
                    Carbon::today()->subDays(6)->toDateString() => 0,
                ]);

                $row->column(6, function (Column $column) use ($week) {
                    $users = User::whereBetween('created_at', [Carbon::tomorrow()->subWeek(), Carbon::tomorrow()->subSecond()])->get();

                    $user_counts = $users->groupBy(function ($item, $key) {
                        return $item->created_at->toDateString();
                    })->transform(function ($item) {
                        return $item->count();
                    });

                    $user_counts = $week->merge($user_counts);

                    $column->append(new Box('近7天用户统计【 商城注册用户共 ' . User::count() . ' 人】', view('admin.pages.index.user', [
                        'user_counts' => $user_counts
                    ])));
                });

                $row->column(6, function (Column $column) use ($week) {
                    $orders = Order::whereBetween('created_at', [Carbon::tomorrow()->subWeek(), Carbon::tomorrow()->subSecond()])->get();

                    $paying_counts = $orders->where('status', Order::ORDER_STATUS_PAYING)->groupBy(function ($item, $key) {
                        return $item->created_at->toDateString();
                    })->transform(function ($item) {
                        return $item->count();
                    });

                    $shipping_counts = $orders->where('status', Order::ORDER_STATUS_SHIPPING)->groupBy(function ($item, $key) {
                        return $item->created_at->toDateString();
                    })->transform(function ($item) {
                        return $item->count();
                    });

                    $receiving_counts = $orders->where('status', Order::ORDER_STATUS_RECEIVING)->groupBy(function ($item, $key) {
                        return $item->created_at->toDateString();
                    })->transform(function ($item) {
                        return $item->count();
                    });

                    $refunding_counts = $orders->where('status', Order::ORDER_STATUS_REFUNDING)->groupBy(function ($item, $key) {
                        return $item->created_at->toDateString();
                    })->transform(function ($item) {
                        return $item->count();
                    });

                    $paying_counts = $week->merge($paying_counts);
                    $shipping_counts = $week->merge($shipping_counts);
                    $receiving_counts = $week->merge($receiving_counts);
                    $refunding_counts = $week->merge($refunding_counts);

                    $column->append(new Box('近7天订单统计【 商城订单共 ' . Order::count() . ' 单】', view('admin.pages.index.order', [
                        'paying_counts' => $paying_counts,
                        'shipping_counts' => $shipping_counts,
                        'receiving_counts' => $receiving_counts,
                        'refunding_counts' => $refunding_counts,
                    ])));
                });
            });
    }

    public function dashboard(Content $content)
    {
        return $content
            ->header('系统信息')
            ->description('信息')
            ->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }
}
