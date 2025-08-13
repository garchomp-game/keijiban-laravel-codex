<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('login is rate limited', function () {
    User::factory()->create([
        'email' => 'test@example.com',
    ]);

    for ($i = 0; $i < 10; $i++) {
        postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'invalid',
        ])->assertStatus(401);
    }

    postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'invalid',
    ])->assertStatus(429);
});
