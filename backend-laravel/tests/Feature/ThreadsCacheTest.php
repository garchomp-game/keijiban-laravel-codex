<?php

use App\Models\Thread;
use App\Models\User;
use App\Support\CacheKeys;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

test('threads index cache is refreshed by version bump', function () {
    Cache::flush();
    Cache::forget('threads:version');

    getJson('/api/threads')->assertOk();

    $user = User::factory()->create();
    Thread::factory()->for($user)->create(['title' => 'hello']);
    CacheKeys::bumpThreadsVersion();

    $res = getJson('/api/threads')->assertOk();
    expect(collect($res->json('data'))->pluck('title'))->toContain('hello');
})->group('cache');
