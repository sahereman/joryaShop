<?php

use App\Models\ShipmentCompany;
use Illuminate\Database\Seeder;

class ShipmentCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        ShipmentCompany::truncate();

        ShipmentCompany::create([
            'code' => 'aae',
            'name' => 'aae全球专递',
        ]);

        ShipmentCompany::create([
            'code' => 'anjie',
            'name' => '安捷快递',
        ]);

        ShipmentCompany::create([
            'code' => 'anxindakuaixi',
            'name' => '安信达快递',
        ]);

        ShipmentCompany::create([
            'code' => 'biaojikuaidi',
            'name' => '彪记快递',
        ]);

        ShipmentCompany::create([
            'code' => 'bht',
            'name' => 'bht',
        ]);

        ShipmentCompany::create([
            'code' => 'baifudongfang',
            'name' => '百福东方国际物流',
        ]);

        ShipmentCompany::create([
            'code' => 'coe',
            'name' => '中国东方（COE）',
        ]);

        ShipmentCompany::create([
            'code' => 'changyuwuliu',
            'name' => '长宇物流',
        ]);

        ShipmentCompany::create([
            'code' => 'datianwuliu',
            'name' => '大田物流',
        ]);

        ShipmentCompany::create([
            'code' => 'debangwuliu',
            'name' => '德邦物流',
        ]);

        ShipmentCompany::create([
            'code' => 'dhl',
            'name' => 'dhl',
        ]);

        ShipmentCompany::create([
            'code' => 'etc',
            'name' => '其他',
        ]);
    }
}
