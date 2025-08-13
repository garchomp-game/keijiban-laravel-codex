<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('user can react to a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user);

    $resp = postJson("/api/posts/{$post->id}/reactions", ['type' => 'like']);
    $resp->assertCreated()->assertJsonPath('data.type', 'like');

    $list = getJson("/api/posts/{$post->id}/reactions");
    $list->assertOk()->assertJsonCount(1, 'data');

    deleteJson("/api/posts/{$post->id}/reactions")->assertNoContent();

    $list = getJson("/api/posts/{$post->id}/reactions");
    $list->assertOk()->assertJsonCount(0, 'data');
});

test('user cannot react twice to same post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user);

    postJson("/api/posts/{$post->id}/reactions", ['type' => 'like'])->assertCreated();

    $resp = postJson("/api/posts/{$post->id}/reactions", ['type' => 'like']);
    $resp->assertStatus(422);
});
