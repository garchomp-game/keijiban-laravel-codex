<?php

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('posts index cache is refreshed by version bump', function () {
    Cache::flush();

    $owner = User::factory()->create();
    $thread = Thread::factory()->for($owner)->create();
    $thread->posts()->create([
        'user_id' => $owner->id,
        'body' => 'first',
    ]);

    getJson("/api/threads/{$thread->id}/posts")->assertOk();

    $user = User::factory()->create();
    actingAs($user);

    postJson("/api/threads/{$thread->id}/posts", ['body' => 'second'])
        ->assertCreated();

    $res = getJson("/api/threads/{$thread->id}/posts")->assertOk();
    expect(collect($res->json('data'))->pluck('body'))->toContain('second');
})->group('cache');
