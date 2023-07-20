<?php

namespace App\Http\Controllers;

use App\Events\NewCommentEvent;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
	public function store(StoreCommentRequest $request, Quote $quote): JsonResponse
	{
		$comment = Comment::create($request->validated() + [
			'quote_id' => $quote->id,
			'user_id'  => Auth::user()->id,
		]);

		$comment->user()->associate(Auth::user());
		$comment->quote()->associate($quote);

		broadcast(new NewCommentEvent($comment, Auth::user(), $quote))->toOthers();

		return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
	}
}
