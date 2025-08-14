<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReactionRequest;
use App\Http\Resources\ReactionResource;
use App\Models\Post;

class ReactionController extends Controller
{
    public function store(ReactionRequest $request, Post $post)
    {
        $reaction = $post->reactions()->create([
            'user_id' => $request->user()->id,
            'type' => $request->string('type'),
        ]);

        return response()->json([
            'data' => new ReactionResource($reaction),
            'meta' => (object) [],
            'error' => null,
        ], 201);
    }
}
