<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Thread;

class PostController extends Controller
{
    public function index(Thread $thread)
    {
        $posts = $thread->posts()->with('user')->latest()->get();
        return response()->json([
            'data' => PostResource::collection($posts),
            'meta' => (object)[],
            'error' => null,
        ]);
    }

    public function store(PostRequest $request, Thread $thread)
    {
        $post = $thread->posts()->create([
            'user_id' => $request->user()->id,
            'body' => $request->string('body'),
        ]);
        return response()->json([
            'data' => new PostResource($post),
            'meta' => (object)[],
            'error' => null,
        ], 201);
    }
}
