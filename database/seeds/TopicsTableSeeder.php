<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Topic;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        // 所有用户 ID 数组，如：[1, 2, 3, 4]
        $userIds = User::all()->pluck('id')->toArray();

        // 所有话题分类 ID 数组，如：[1, 2, 3, 4]
        $categoryIds = Category::all()->pluck('id')->toArray();

        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        // 生成数据集合
        $topics = factory(Topic::class)
                        ->times(100)
                        ->make()
                        ->each(function ($topic, $index)
                            use ($faker, $userIds, $categoryIds)
        {
            // 从用户 ID 数组中随机取一个并赋值
            $topic->user_id = $faker->randomElement($userIds);

            // 话题分类，同上
            $topic->category_id = $faker->randomElement($categoryIds);
        });

        // 将数据集合转化为数组，并插入数据库
        Topic::insert($topics->toArray());
    }

}

