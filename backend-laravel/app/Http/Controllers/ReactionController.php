<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReactionResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReactionController extends Controller
{
    public function index(Post $post)
    {
        $reactions = $post->reactions()->with('user')->get();

        return response()->json([
            'data' => ReactionResource::collection($reactions),
            'meta' => (object) [],
            'error' => null,
        ]);
    }

    public function store(Request $request, Post $post)
    {
        validator(
            ['type' => $request->input('type'), 'user_id' => $request->user()->id],
            [
                'type' => ['required', 'string'],
                'user_id' => [
                    Rule::unique('reactions')->where(fn ($q) => $q->where('post_id', $post->id)),
                ],
            ]
        )->validate();

        $reaction = $post->reactions()->create([
            'user_id' => $request->user()->id,
            'type' => $request->string('type'),
        ])->load('user');

        return response()->json([
            'data' => new ReactionResource($reaction),
            'meta' => (object) [],
            'error' => null,
        ], 201);
    }

    public function destroy(Request $request, Post $post)
    {
        $reaction = $post->reactions()->where('user_id', $request->user()->id)->first();
        if ($reaction) {
            $reaction->delete();
        }

        return response()->noContent();
    }
}
