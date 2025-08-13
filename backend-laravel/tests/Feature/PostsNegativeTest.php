<?php

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('creating post requires body', function () {
    $user = User::factory()->create();
    $thread = Thread::factory()->create();
    actingAs($user);

    postJson("/api/threads/{$thread->id}/posts", [])->assertStatus(422);
});
