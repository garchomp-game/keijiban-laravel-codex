<?php

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

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
