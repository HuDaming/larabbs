<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CalculateActiveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Larabbs:calculate-active-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '统计活跃用户';

    /**
     * Execute the console command.
     *
     * @param $user
     * @return mixed
     */
    public function handle(User $user)
    {
        // 在命令行打印一行信息
        $this->info("开始计算...");

        $user->calculateAndCacheActiveUsers();

        $this->info("成功生成！");
    }
}
