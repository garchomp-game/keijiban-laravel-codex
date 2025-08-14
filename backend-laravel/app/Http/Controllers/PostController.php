<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Thread;
use App\Support\CacheKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index(Request $request, Thread $thread)
    {
        $page = (int) $request->query('page', 1);
        $ttl = (int) config('cache.ttl.posts_index', 60);
        $key = CacheKeys::postsIndexKey($thread->id, $page);

        $paginator = Cache::remember($key, now()->addSeconds($ttl), function () use ($thread) {
            return $thread->posts()->with('user')->latest()->paginate(20);
        });

        return response()->json([
            'data' => PostResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
            ],
            'error' => null,
        ]);
    }

    public function store(PostRequest $request, Thread $thread)
    {
        $post = $thread->posts()->create([
            'user_id' => $request->user()->id,
            'body' => $request->string('body'),
        ]);

        CacheKeys::bumpPostsVersion($thread->id);

        return response()->json([
            'data' => new PostResource($post),
            'meta' => (object) [],
            'error' => null,
        ], 201);
    }

    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $post->update(['body' => $request->string('body')]);

        CacheKeys::bumpPostsVersion($post->thread_id);

        return response()->json([
            'data' => new PostResource($post),
            'meta' => (object) [],
            'error' => null,
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $threadId = $post->thread_id;
        $post->delete();

        CacheKeys::bumpPostsVersion($threadId);

        return response()->noContent();
    }
}
