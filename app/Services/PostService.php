<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostService
{
    public function createPost($data)
    {
        try {
            $post = Post::create([
                'title' => $data['title'],
                'content' => $data['content'],
                'user_id' => Auth::id(),
            ]);
            return $post;
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            throw new \Exception('Unable to create post. Please try again.');
        }
    }

    public function updatePost($post, $data)
    {
        try {
            $post->update($data);
            return $post;
        } catch (\Exception $e) {
            Log::error('Error updating post: ' . $e->getMessage());
            throw new \Exception('Unable to update post. Please try again.');
        }
    }

    public function deletePost(Post $post, $userId)
    {
        if ($post->user_id !== $userId) {
            throw new \Exception('You are not authorized to delete this post.');
        }

        try {
            $post->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            throw new \Exception('Failed to delete the post.');
        }
    }
}