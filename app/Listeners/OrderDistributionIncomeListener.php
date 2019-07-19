<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Models\DistributionLevel;
use App\Models\ExchangeRate;
use App\Models\User;
use App\Models\UserMoneyBill;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class OrderDistributionIncomeListener
{
    /**
     * Create the event listener.
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @param  OrderCompletedEvent $event
     * @return void
     */
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->getOrder();
        $levels = DistributionLevel::orderBy('level', 'asc')->get();

        $levels->each(function ($item) use ($order) {

            $user = $order->user;
            $income_user = null;

            // 获取多级别分销用户中,当前收益用户
            for ($i = 1; $i <= $item->level; $i++)
            {
                if ($i == 1)
                {
                    $income_user = $user->income_user;
                } elseif ($income_user == null)
                {
                    break;
                } else
                {
                    $income_user = $income_user->income_user;
                }

            }

            if ($income_user instanceof User)
            {
                // 收益率乘数
                $ration = $item->profit_ratio / 100;

                // 转换成美元金额  分销收益
                $USD_money = exchange_price($order->total_amount, 'USD', $order->currency);

                // 收益金额
                $income_money = bcmul($USD_money, $ration, 2);

                // 更新用户金额
                $income_user->update([
                    'money' => bcadd($income_user->money, $income_money, 2)
                ]);

                // 记录账单
                $umBill = new UserMoneyBill();
                $umBill->change($income_user,
                    $umBill::TYPE_DISTRIBUTION_INCOME,
                    ExchangeRate::USD,
                    $income_money);
            }

        });

        // 分销收益计算完成

    }
}
