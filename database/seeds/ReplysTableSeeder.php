<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Topic;
use App\Models\Reply;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::all()->pluck('id')->toArray();
        $topicIds = Topic::all()->pluck('id')->toArray();

        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        $replys = factory(Reply::class)
                        ->times(1000)
                        ->make()
                        ->each(function ($reply, $index)
                            use ($faker, $userIds, $topicIds)
        {
            // 随机取一个用户 ID 赋值给 Reply 的 user_id
            $reply->user_id = $faker->randomElement($userIds);
            // 随机取一个话题 ID 赋值给 Reply 的 topic_id
            $reply->topic_id = $faker->randomElement($topicIds);
        });

        // 将数据集合转换为数组，写入数据库
        Reply::insert($replys->toArray());
    }

}

