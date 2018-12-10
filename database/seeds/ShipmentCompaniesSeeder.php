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

        /*ShipmentCompany::create([
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
            'code' => 'shentong',
            'name' => '申通',
        ]);

        ShipmentCompany::create([
            'code' => 'shunfeng',
            'name' => '顺丰',
        ]);

        ShipmentCompany::create([
            'code' => 'sue',
            'name' => '速尔物流',
        ]);

        ShipmentCompany::create([
            'code' => 'shengfeng',
            'name' => '盛丰物流',
        ]);

        ShipmentCompany::create([
            'code' => 'saiaodi',
            'name' => '赛澳递',
        ]);

        ShipmentCompany::create([
            'code' => 'tiandihuayu',
            'name' => '天地华宇',
        ]);

        ShipmentCompany::create([
            'code' => 'tiantian',
            'name' => '天天快递',
        ]);

        ShipmentCompany::create([
            'code' => 'tnt',
            'name' => 'tnt',
        ]);

        ShipmentCompany::create([
            'code' => 'ups',
            'name' => 'ups',
        ]);

        ShipmentCompany::create([
            'code' => 'youshuwuliu',
            'name' => '优速物流',
        ]);

        ShipmentCompany::create([
            'code' => 'yuantong',
            'name' => '圆通速递',
        ]);

        ShipmentCompany::create([
            'code' => 'yunda',
            'name' => '韵达快运',
        ]);

        ShipmentCompany::create([
            'code' => 'zhongtong',
            'name' => '中通速递',
        ]);*/

        // $shipment_companies = require('../demo/ShipmentCompanies.php');
        $shipment_companies = require(database_path('demo/ShipmentCompanies.php'));

        foreach($shipment_companies as $shipment_company){
            ShipmentCompany::create([
                'code' => $shipment_company['code'],
                'name' => $shipment_company['name'],
            ]);
        }

        ShipmentCompany::create([
            'code' => 'etc',
            'name' => '其他',
        ]);
    }
}
