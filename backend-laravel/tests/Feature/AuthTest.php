<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{postJson,getJson};

uses(RefreshDatabase::class);

test('user can register, login, fetch profile and logout', function () {
    $register = postJson('/api/auth/register', [
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);
    $register->assertCreated()->assertJsonPath('data.email', 'test@example.com');

    $login = postJson('/api/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);
    $login->assertNoContent();
    $cookie = $login->cookies->get(session_name());

    $user = getJson('/api/user', [], [
        'Cookie' => session_name().'='.$cookie->getValue(),
    ]);
    $user->assertOk()->assertJsonPath('data.email', 'test@example.com');

    $logout = postJson('/api/auth/logout', [], [
        'Cookie' => session_name().'='.$cookie->getValue(),
    ]);
    $logout->assertNoContent();
});
