<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('authenticated user can manage a thread', function () {
    $user = User::factory()->create();
    actingAs($user);

    $create = postJson('/api/threads', ['title' => 'My Thread']);
    $create->assertCreated();
    $id = $create->json('data.id');

    $update = patchJson("/api/threads/{$id}", ['title' => 'Updated']);
    $update->assertOk()->assertJsonPath('data.title', 'Updated');

    $show = getJson("/api/threads/{$id}");
    $show->assertOk()->assertJsonPath('data.title', 'Updated');

    $delete = deleteJson("/api/threads/{$id}");
    $delete->assertNoContent();
});
