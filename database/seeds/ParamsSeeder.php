<?php

use App\Models\Param;
use Illuminate\Database\Seeder;

class ParamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            'Hue',
            'Line',
            'Style'
        ];
        foreach ($params as $key => $param) {
            factory(Param::class)->create([
                'name' => $param,
                'sort' => $key + 1
            ]);
        }
    }
}
