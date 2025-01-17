<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(): JsonResponse
    {
        try {
            $posts = Post::where('user_id', auth()->id())->get();

            return response()->json([
                'status' => 'success',
                'data' => $posts,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching posts: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch posts.',
                'data' => null,
            ], 500);
        }
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        try {
            $post = $this->postService->createPost($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully.',
                'data' => $post,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        try {
            $updatedPost = $this->postService->updatePost($post, $request->validated(), auth()->id());

            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully.',
                'data' => $updatedPost,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], $e->getMessage() === 'You are not authorized to update this post.' ? 403 : 500);
        }
    }

    public function destroy(Post $post): JsonResponse
    {
        try {
            $this->postService->deletePost($post, auth()->id());

            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully.',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], $e->getMessage() === 'You are not authorized to delete this post.' ? 403 : 500);
        }
    }
}
