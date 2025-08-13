<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class CacheKeys
{
    public static function threadsIndexKey(int $page = 1): string
    {
        $v = Cache::get('threads:version', 1);

        return "threads:index:v{$v}:p{$page}";
    }

    public static function postsIndexKey(int $threadId, int $page = 1): string
    {
        $v = Cache::get("thread:{$threadId}:posts:version", 1);

        return "thread:{$threadId}:posts:v{$v}:p{$page}";
    }

    public static function bumpThreadsVersion(): void
    {
        // increment が false を返す可能性に備え、初期化を保証
        if (Cache::add('threads:version', 2)) {
            return;
        }
        Cache::increment('threads:version');
    }

    public static function bumpPostsVersion(int $threadId): void
    {
        $key = "thread:{$threadId}:posts:version";
        if (Cache::add($key, 2)) {
            return;
        }
        Cache::increment($key);
    }
}
