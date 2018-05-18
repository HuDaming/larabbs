<?php

namespace App\Observers;

use App\Models\Link;
use Cache;

class LinkObserver
{
    // 在保存时清空 cacheKey 对应的缓存
    public function saved(Link $link)
    {
        Cache::forget($link->cacheKey);
    }
}