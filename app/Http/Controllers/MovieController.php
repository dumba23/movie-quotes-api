<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
	public function allMovies(): AnonymousResourceCollection
	{
		return MovieResource::collection(Movie::all());
	}

	public function index(): AnonymousResourceCollection
	{
		return MovieResource::collection(auth()->user()->movies);
	}

	public function show(Movie $movie): MovieResource | JsonResponse
	{
		$user = Auth::user();

		if ($movie->user_id !== $user->id) {
			return response()->json(['Invalid request'], 403);
		}

		return new MovieResource($movie);
	}

	public function store(StoreMovieRequest $request, Movie $movie): MovieResource
	{
		$newMovie = DB::transaction(function () use ($movie, $request) {
			$movie = Movie::create($request->validated() + [
				'image'   => config('app.url') . '/storage/' . $request->file('image')->store('images'),
				'user_id' => auth()->id(),
			]);

			$movie->genres()->attach($request->input('genreIds'));

			return $movie;
		});

		return MovieResource::make($newMovie);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): MovieResource
	{
		$updatedMovie = DB::transaction(function () use ($request, $movie) {
			$validatedData = $request->validated();

			if ($request->hasFile('image')) {
				$validatedData['image'] = config('app.url') . '/storage/' . $request->file('image')->store('images');
			}

			$movie->update($validatedData);
			$movie->genres()->detach();
			$movie->genres()->attach($request->input('genreIds'));

			return $movie;
		});

		return MovieResource::make($updatedMovie);
	}

	public function destroy(string $movieId): JsonResponse
	{
		$movie = Movie::findOrFail($movieId);
		$movie->delete();

		return response()->json(['message' => 'Movie deleted successfully']);
	}
}
