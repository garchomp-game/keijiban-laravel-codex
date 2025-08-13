<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('register requires fields', function () {
    postJson('/api/auth/register', [])->assertStatus(422);
});

test('logout requires authentication', function () {
    postJson('/api/auth/logout')->assertStatus(401);
});

test('fetching user requires authentication', function () {
    getJson('/api/user')->assertStatus(401);
});
