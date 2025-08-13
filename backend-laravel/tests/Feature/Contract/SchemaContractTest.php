<?php

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

test('listThreads returns expected structure', function () {
    $user = User::factory()->create();
    Post::factory()->for($user)->for(Thread::factory()->for($user))->create();

    $response = getJson('/api/threads');

    $response->assertOk()->assertJsonStructure([
        'data' => [[
            'id',
            'title',
            'body',
            'user' => ['id', 'name'],
        ]],
        'links',
        'meta',
    ]);
});

test('listPosts returns expected structure', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for($user)->create();
    Post::factory()->for($user)->for($thread)->create();

    $response = getJson("/api/threads/{$thread->id}/posts");

    $response->assertOk()->assertJsonStructure([
        'data' => [[
            'id',
            'body',
            'thread_id',
            'user' => ['id', 'name'],
        ]],
        'links',
        'meta',
    ]);
});

test('showThread returns expected structure', function () {
    $user = User::factory()->create();
    Post::factory()->for($user)->for($thread = Thread::factory()->for($user)->create())->create();

    $response = getJson("/api/threads/{$thread->id}");

    $response->assertOk()->assertJsonStructure([
        'data' => [
            'id',
            'title',
            'body',
            'user' => ['id', 'name'],
        ],
    ]);
});
