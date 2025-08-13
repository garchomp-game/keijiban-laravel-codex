<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ThreadsAuthzTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_update_delete_threads(): void
    {
        $owner = User::factory()->create();
        $thread = Thread::factory()->for($owner)->create();

        $this->postJson('/api/threads', ['title' => 'x'])
            ->assertStatus(401);

        $this->patchJson("/api/threads/{$thread->id}", ['title' => 'z'])
            ->assertStatus(401);

        $this->deleteJson("/api/threads/{$thread->id}")
            ->assertStatus(401);
    }

    public function test_non_owner_gets_403_on_update_delete(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $thread = Thread::factory()->for($owner)->create();

        Sanctum::actingAs($intruder);

        $this->patchJson("/api/threads/{$thread->id}", ['title' => 'intrude'])
            ->assertStatus(403);

        $this->deleteJson("/api/threads/{$thread->id}")
            ->assertStatus(403);
    }

    public function test_owner_can_update_and_delete(): void
    {
        $owner = User::factory()->create();
        $thread = Thread::factory()->for($owner)->create();

        Sanctum::actingAs($owner);

        $this->patchJson("/api/threads/{$thread->id}", ['title' => 'updated'])
            ->assertStatus(200);

        $this->deleteJson("/api/threads/{$thread->id}")
            ->assertStatus(204);
    }

    public function test_public_get_endpoints_are_accessible_without_auth(): void
    {
        $owner = User::factory()->create();
        $thread = Thread::factory()->for($owner)->create();

        $this->getJson('/api/threads')->assertStatus(200);
        $this->getJson("/api/threads/{$thread->id}")->assertStatus(200);
        $this->getJson("/api/threads/{$thread->id}/posts")->assertStatus(200);
    }
}
