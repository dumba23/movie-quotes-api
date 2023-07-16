<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use App\Services\MovieService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
	protected MovieService $movieService;

	public function __construct(MovieService $movieService)
	{
		$this->movieService = $movieService;
	}

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
		$newMovie = $this->movieService->createMovie($request, $movie);

		return MovieResource::make($newMovie);
	}

	public function destroy(string $movieId): JsonResponse
	{
		$this->movieService->deleteMovie($movieId);

		return response()->json(['message' => 'Movie deleted successfully']);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): MovieResource
	{
		$updatedMovie = $this->movieService->updateMovie($request, $movie);

		return MovieResource::make($updatedMovie);
	}
}
