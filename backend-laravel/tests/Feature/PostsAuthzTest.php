<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostsAuthzTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_post(): void
    {
        $owner = User::factory()->create();
        $thread = Thread::factory()->for($owner)->create();

        $this->postJson("/api/threads/{$thread->id}/posts", ['body' => 'hello'])
            ->assertStatus(401);
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $thread = Thread::factory()->for($owner)->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/threads/{$thread->id}/posts", ['body' => 'hello'])
            ->assertStatus(201);
    }
}
