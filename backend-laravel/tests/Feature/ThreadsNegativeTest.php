<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('fetching non-existent thread returns 404', function () {
    getJson('/api/threads/999999')->assertStatus(404);
});

test('creating thread requires title', function () {
    $user = User::factory()->create();
    actingAs($user);

    postJson('/api/threads', [])->assertStatus(422);
});
