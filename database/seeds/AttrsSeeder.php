<?php

use App\Models\Attr;
use Illuminate\Database\Seeder;

class AttrsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attrs = [
            'Base Size',
            'Hair Color',
            'Hair Density'
        ];
        foreach ($attrs as $key => $attr) {
            factory(Attr::class)->create([
                'name' => $attr,
                'sort' => $key + 1
            ]);
        }
    }
}
