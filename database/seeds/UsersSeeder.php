<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // 通过 factory 方法生成 x 个数据并保存到数据库中
        factory(User::class, 5)->create();


        //测试账号
        $user = User::find(1);
        $user->name = 'aaa';
        $user->email = 'aaa@163.com';
        $user->password = bcrypt('123456');
        $user->save();

        $user = User::find(2);
        $user->name = 'bbb';
        $user->email = 'bbb@163.com';
        $user->password = bcrypt('123456');
        $user->save();

        $user = User::find(3);
        $user->name = 'ccc';
        $user->email = 'ccc@163.com';
        $user->password = bcrypt('123456');
        $user->save();
    }
}
