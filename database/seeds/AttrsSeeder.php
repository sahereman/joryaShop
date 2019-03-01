<?php

/*商品属性 2019-03-01*/
//use App\Models\Attr;
//use Illuminate\Database\Seeder;
//
//class AttrsSeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     * @return void
//     */
//    public function run()
//    {
//        $attrs = require(database_path('demo/Attrs.php')); // It works.
//
//        foreach ($attrs as $parent => $children) {
//            $parent_model = factory(Attr::class)->create([
//                'parent_id' => 0,
//                'name_en' => $parent,
//                'name_zh' => $parent,
//            ]);
//            foreach ($children as $child) {
//                factory(Attr::class)->create([
//                    'parent_id' => $parent_model->id,
//                    'name_en' => $child,
//                    'name_zh' => $child,
//                ]);
//            }
//        }
//    }
//}
/*商品属性 2019-03-01*/
