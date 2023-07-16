<?php

namespace App\Http\Controllers;

use App\Events\NewLikeEvent;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
	public function allQuotes(): AnonymousResourceCollection
	{
		$quotes = Quote::with('movie.user')->orderBy('created_at', 'desc')->get();

		return QuoteResource::collection($quotes);
	}

	public function index(): AnonymousResourceCollection
	{
		$userId = Auth::id();
		$quotes = Quote::whereIn('movie_id', function ($query) use ($userId) {
			$query->select('id')->from('movies')->where('user_id', $userId);
		})->orderBy('created_at', 'desc')->get();

		return QuoteResource::collection($quotes);
	}

	public function show(Quote $quote): QuoteResource
	{
		return QuoteResource::make($quote);
	}

	public function store(StoreQuoteRequest $request, Quote $quote): QuoteResource
	{
		$quote->image = config('app.url') . '/storage/' . $request->file('image')->store('images');
		$quote->movie_id = $request->movie_id;
		$quote->setTranslations('title', [
			'en' => $request->title_en,
			'ka' => $request->title_ka,
		]);

		$quote->save();

		return QuoteResource::make($quote);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote): QuoteResource
	{
		if ($request->hasFile('image')) {
			Storage::delete($quote->image);

			$quote->image = config('app.url') . '/storage/' . $request->file('image')->store('images');
		}

		$quote->setTranslations('title', [
			'en' => $request->title_en,
			'ka' => $request->title_ka,
		]);

		$quote->save();

		return QuoteResource::make($quote);
	}

	public function destroy(Quote $quote): JsonResponse
	{
		Storage::delete($quote->image);
		$quote->delete();

		return response()->json(['message' => 'Quote deleted successfully']);
	}

	public function like(Quote $quote): JsonResponse
	{
		$user = Auth::user();
		$toggle = $quote->likes()->toggle($user->id);

		if ($toggle['attached']) {
			broadcast(new NewLikeEvent($quote, $user, true));
		} else {
			broadcast(new NewLikeEvent($quote, $user, false));
			Notification::where('user_id', $user->id)
				->where('quote_id', $quote->id)
				->delete();
		}

		return response()->json(['message' => 'Quote like toggled successfully']);
	}
}
