<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use App\Services\MovieService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController extends Controller
{
	protected MovieService $movieService;

	public function __construct(MovieService $movieService)
	{
		$this->movieService = $movieService;
	}

	public function index(): AnonymousResourceCollection
    {
		return MovieResource::collection(auth()->user()->movies);
	}

	public function show(Movie $movie): JsonResponse
	{
		return response()->json(([
			'id'           => $movie->id,
			'title'        => $movie->getTranslations('title'),
			'director'     => $movie->getTranslations('director'),
			'description'  => $movie->getTranslations('description'),
			'image'        => $movie->image,
			'genres'       => $movie->genres,
			'release_date' => $movie->release_date,
		]));
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
