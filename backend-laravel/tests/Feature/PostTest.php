<?php

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

test('user can create posts for thread', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for($user)->create();
    actingAs($user);

    $resp = postJson("/api/threads/{$thread->id}/posts", ['body' => 'Hello']);
    $resp->assertCreated()->assertJsonPath('data.body', 'Hello');

    $list = getJson("/api/threads/{$thread->id}/posts");
    $list->assertOk()->assertJsonPath('data.0.body', 'Hello');
});

test('user can update a post', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for($user)->create();
    $post = Post::factory()->for($thread)->for($user)->create([
        'body' => 'Hello',
    ]);
    actingAs($user);

    $resp = putJson("/api/posts/{$post->id}", ['body' => 'Updated']);
    $resp->assertOk()->assertJsonPath('data.body', 'Updated');
});

test('user can delete a post', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->for($user)->create();
    $post = Post::factory()->for($thread)->for($user)->create([
        'body' => 'Hello',
    ]);
    actingAs($user);

    deleteJson("/api/posts/{$post->id}")->assertNoContent();

    getJson("/api/threads/{$thread->id}/posts")
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
