<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
	public function index(): JsonResponse
	{
		return response()->json(MovieResource::collection(auth()->user()->movies));
	}

	public function get(Movie $movie): JsonResponse
	{
		return response()->json(MovieResource::make($movie));
	}

	public function store(StoreMovieRequest $request): JsonResponse
	{
        $movie = DB::transaction(fn () => tap(
            Movie::create(
                $request->validated() +
                [
                'image' => '/storage/' . $request->file('image')->store('images'),
                'user_id' => auth()->id(),
            ]),
            fn ($movie) => $movie->genres()->attach($request->input('genreIds'))
        ));

		return response()->json(MovieResource::make($movie));
	}

    public function destroy(Movie $movie): JsonResponse
    {
        $movie->delete();

        return response()->json(['message' => 'Movie deleted successfully']);
    }

    public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
    {
        $movie->update($request->validated());

        return response()->json(MovieResource::make($movie));
    }
}
