<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Jobs\TouchCacheJob;

test('redis is reachable and cache works', function () {
    if (config('cache.default') !== 'redis') {
        $this->markTestSkipped('redis cache not enabled');
    }
    Cache::put('ci:redis', 'ok', 10);
    expect(Cache::get('ci:redis'))->toBe('ok');

    expect((string) Redis::connection()->ping())->toBe('PONG');
})->group('redis');

test('redis queue processes a job once', function () {
    if (config('queue.default') !== 'redis') {
        $this->markTestSkipped('redis queue not enabled');
    }
    Cache::forget('job:touched');
    dispatch(new TouchCacheJob('job:touched'));
    Artisan::call('queue:work', ['--once' => true, '--no-interaction' => true, '--stop-when-empty' => true]);
    expect(Cache::has('job:touched'))->toBeTrue();
})->group('redis');
