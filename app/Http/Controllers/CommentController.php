<?php

namespace App\Http\Controllers;

use App\Events\NewCommentEvent;
use App\Models\Comment;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
	public function store(Request $request, Quote $quote): JsonResponse
	{
		$comment = Comment::create([
			'content' => $request->input('content'),
		]);

		$comment->user()->associate(Auth::user());
		$comment->quote()->associate($quote);

		broadcast(new NewCommentEvent($comment, Auth::user(), $quote));

		return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
	}
}
