<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    public function test_user_can_update_their_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $token = auth('api')->login($user);

        $response = $this->putJson("/api/v1/posts/{$post->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content with more than 10 characters.',
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Post updated successfully.',
                'data' => [
                    'title' => 'Updated Title',
                    'content' => 'Updated content with more than 10 characters.',
                ],
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'content' => 'Updated content with more than 10 characters.',
        ]);
    }

    public function test_user_cannot_update_other_users_post()
    {
        $first_user = User::factory()->create();
        $second_user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $second_user->id]);

        $token = auth('api')->login($first_user);

        $response = $this->putJson("/api/v1/posts/{$post->id}", [
            'title' => 'Unauthorized Update',
            'content' => 'This should not be allowed.',
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 'error',
                'message' => 'You are not authorized to update this post.',
            ]);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'title' => 'Unauthorized Update',
            'content' => 'This should not be allowed.',
        ]);
    }

    public function test_user_can_delete_their_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $token = auth('api')->login($user);

        $response = $this->deleteJson("/api/v1/posts/{$post->id}", [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Post deleted successfully.',
            ]);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_other_users_post()
    {
        $first_user = User::factory()->create();
        $second_user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $second_user->id]);

        $token = auth('api')->login($first_user);

        $response = $this->deleteJson("/api/v1/posts/{$post->id}", [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 'error',
                'message' => 'You are not authorized to delete this post.',
            ]);

        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
