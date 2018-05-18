<?php

namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;

trait ActiveUserHelper
{
    // 用于存放临时用户数据
    protected $users = [];

    // 配置信息
    protected $topicWeight = 4; // 话题权重
    protected $replyWeight = 1; // 回复权重
    protected $passDays = 7; // 统计周期（多少天内发布过内容）
    protected $userNumber = 6; // 取出来多少用户

    // 缓存相关配置
    protected $cacheKey = 'larabbs_active_users';
    protected $cacheExpireInMinutes = 65;

    public function getActiveUsers()
    {
        // 尝试从缓存中读取 cacheKey 对应的数据，如果能取到便直接返回。
        // 否则运行匿名函数中的代码取出活跃用户数据，返回的同时写入缓存
        return Cache::remember($this->cacheKey, $this->cacheExpireInMinutes, function () {
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        // 取得活跃用户列表
        $activeUsers = $this->calculateActiveUsers();
        // 写缓存
        $this->cacheActiveUsers($activeUsers);
    }

    public function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        // 数组按照得分排序
        $users = array_sort($this->users, function ($user) {
            return $user['score'];
        });

        // 我们需要的是倒序，高分靠前，第二个参数为保持数据的 KEY 不变
        $users = array_reverse($users, true);

        // 只获取我们想要的数量
        $users = array_slice($users, 0, $this->userNumber, true);

        // 新建一个空集合
        $acriveUsers = collect();

        foreach ($users as $user_id => $user) {
            // 检查用户是否存在
            $user = $this->find($user_id);

            // 如果用户存在
            if ($user) {
                // 保存用户
                $acriveUsers->push($user);
            }
        }

        // 返回数据
        return $acriveUsers;
    }

    private function calculateTopicScore()
    {
        // 从话题数据表中取出限定时间范围（$passDays）内，发表过话题的用户，同时取出用户此段时间内发布话题的数量
        $topicUsers = Topic::query()->select(DB::raw('user_id, COUNT(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->passDays))
            ->groupBy('user_id')
            ->get();

        // 根据话题数量计算得分
        foreach ($topicUsers as $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topicWeight;
        }
    }

    private function calculateReplyScore()
    {
        // 从回复数据表中取得限定时间范围（$passDays）内，发表过回复的用户，同时取出用户这段时间内发布回复的数量
        $replyUsers = Reply::query()->select(DB::raw('user_id, COUNT(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->passDays))
            ->groupBy('user_id')
            ->get();

        // 根据回复数量计算得分
        foreach ($replyUsers as $value) {
            $replyScore = $value->reply_count * $this->replyWeight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $replyScore;
            } else {
                $this->users[$value->user_id]['score'] = $replyScore;
            }
        }
    }

    /**
     * 活跃用户数据写入缓存
     *
     * @param $activeUsers
     */
    private function cacheActiveUsers($activeUsers)
    {
        // 将数据写入缓存
        Cache::put($this->cacheKey, $activeUsers, $this->cacheExpireInMinutes);
    }
}

