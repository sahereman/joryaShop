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
            [
                'name' => 'Base Size',
                'has_photo' => false,
                'sort' => 3
            ],
            [
                'name' => 'Hair Color',
                'has_photo' => true,
                'sort' => 2
            ],
            [
                'name' => 'Hair Density',
                'has_photo' => false,
                'sort' => 1
            ]
        ];
        foreach ($attrs as $attr) {
            factory(Attr::class)->create([
                'name' => $attr['name'],
                'has_photo' => $attr['has_photo'],
                'sort' => $attr['sort']
            ]);
        }
    }
}
