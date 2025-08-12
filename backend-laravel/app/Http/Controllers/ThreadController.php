<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadRequest;
use App\Http\Resources\ThreadResource;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::with('user')->latest()->get();
        return response()->json([
            'data' => ThreadResource::collection($threads),
            'meta' => (object)[],
            'error' => null,
        ]);
    }

    public function store(ThreadRequest $request)
    {
        $thread = Thread::create([
            'user_id' => $request->user()->id,
            'title' => $request->string('title'),
        ]);
        return response()->json([
            'data' => new ThreadResource($thread),
            'meta' => (object)[],
            'error' => null,
        ], 201);
    }

    public function show(Thread $thread)
    {
        $thread->load('user');
        return response()->json([
            'data' => new ThreadResource($thread),
            'meta' => (object)[],
            'error' => null,
        ]);
    }

    public function update(ThreadRequest $request, Thread $thread)
    {
        $this->authorize('update', $thread);
        $thread->update(['title' => $request->string('title')]);
        return response()->json([
            'data' => new ThreadResource($thread),
            'meta' => (object)[],
            'error' => null,
        ]);
    }

    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);
        $thread->delete();
        return response()->noContent();
    }
}
