<?php

use Illuminate\Database\Seeder;

class RefundReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $refund_reasons = require(database_path('demo/RefundReasons.php'));

        foreach ($refund_reasons as $key => $refund_reason) {
            factory(\App\Models\RefundReason::class)->create([
                'reason_en' => $refund_reason['reason_en'],
                'reason_zh' => $refund_reason['reason_zh'],
                'sort' => $refund_reason['sort'],
            ]);
        }
    }
}
