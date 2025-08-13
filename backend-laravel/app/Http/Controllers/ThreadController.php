<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadRequest;
use App\Http\Resources\ThreadResource;
use App\Models\Thread;
use App\Support\CacheKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThreadController extends Controller
{
    public function index(Request $request)
    {
        $page = (int) $request->query('page', 1);
        $ttl = (int) config('cache.ttl.threads_index', 60);
        $key = CacheKeys::threadsIndexKey($page);

        $paginator = Cache::remember($key, now()->addSeconds($ttl), function () {
            return Thread::with('user')->latest()->paginate(20);
        });

        return response()->json([
            'data' => ThreadResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
            ],
            'error' => null,
        ]);
    }

    public function store(ThreadRequest $request)
    {
        $thread = Thread::create([
            'user_id' => $request->user()->id,
            'title' => $request->string('title'),
        ]);

        CacheKeys::bumpThreadsVersion();

        return response()->json([
            'data' => new ThreadResource($thread),
            'meta' => (object) [],
            'error' => null,
        ], 201);
    }

    public function show(Thread $thread)
    {
        $thread->load('user');

        return response()->json([
            'data' => new ThreadResource($thread),
            'meta' => (object) [],
            'error' => null,
        ]);
    }

    public function update(ThreadRequest $request, Thread $thread)
    {
        $this->authorize('update', $thread);
        $thread->update(['title' => $request->string('title')]);

        CacheKeys::bumpThreadsVersion();

        return response()->json([
            'data' => new ThreadResource($thread),
            'meta' => (object) [],
            'error' => null,
        ]);
    }

    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);
        $thread->delete();

        CacheKeys::bumpThreadsVersion();

        return response()->noContent();
    }
}
