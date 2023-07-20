<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
	public function index(): AnonymousResourceCollection
	{
		return MovieResource::collection(Movie::all());
	}

	public function authorizedUserMovies(): AnonymousResourceCollection
	{
		return MovieResource::collection(auth()->user()->movies);
	}

	public function get(Movie $movie): MovieResource | JsonResponse
	{
		$this->authorize('get', $movie);

		return new MovieResource($movie);
	}

	public function store(StoreMovieRequest $request, Movie $movie): MovieResource
	{
		$newMovie = DB::transaction(function () use ($movie, $request) {
			$validatedData = $request->validated();

			$validatedData['image'] = config('app.url') . '/storage/' . $request->file('image')->store('images');

			$movie = Movie::create($validatedData + [
				'user_id' => auth()->id(),
			]);

			$movie->genres()->attach($request->genreIds);

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
			$movie->genres()->attach($request->genreIds);

			return $movie;
		});

		return MovieResource::make($updatedMovie);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();

		return response()->json(['message' => 'Movie deleted successfully']);
	}
}
