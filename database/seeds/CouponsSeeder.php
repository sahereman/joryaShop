<?php

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coupons = [
            [
                'type' => 'discount',
                'discount' => '0.10',
                'number' => null,
                'designated_product_types' => ['common']
            ],
            [
                'type' => 'reduction',
                'reduction' => '100',
                'number' => 100,
                'designated_product_types' => ['common', 'period']
            ],
            [
                'type' => 'discount',
                'discount' => '0.20',
                'number' => 200,
                'designated_product_types' => ['common', 'auction']
            ],
            [
                'type' => 'reduction',
                'reduction' => '200',
                'number' => null,
                'designated_product_types' => ['common', 'period', 'auction']
            ],
            [
                'type' => 'discount',
                'discount' => '0.30',
                'number' => 300,
                'designated_product_types' => ['period', 'auction']
            ],
            [
                'type' => 'reduction',
                'reduction' => '300',
                'number' => null,
                'designated_product_types' => ['period']
            ],
            [
                'type' => 'discount',
                'discount' => '0.40',
                'number' => 400,
                'designated_product_types' => ['auction']
            ],
        ];

        foreach ($coupons as $key => $coupon) {
            $coupon['sort'] = $key;
            $coupon['allowance'] = $key + 1;
            $coupon['threshold'] = 1000 * ($key + 1);
            $coupon['name'] = 'Coupon No.' . ($key + 1);
            factory(Coupon::class)->create($coupon);
        }
    }
}
