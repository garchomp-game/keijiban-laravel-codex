<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use App\Support\CacheKeys;
use App\Models\User;
use App\Models\Thread;

uses(RefreshDatabase::class);

test('threads index cache is refreshed by version bump', function () {
    Cache::flush();
    Cache::forget('threads:version');

    $this->getJson('/api/threads')->assertOk();

    $user = User::factory()->create();
    Thread::factory()->for($user)->create(['title' => 'hello']);
    CacheKeys::bumpThreadsVersion();

    $res = $this->getJson('/api/threads')->assertOk();
    expect(collect($res->json('data'))->pluck('title'))->toContain('hello');
})->group('cache');
