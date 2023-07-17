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
            $movie->image =  config('app.url') . '/storage/' . $request->file('image')->store('images');
            $movie->user_id = auth()->id();
            $movie->release_date = $request->release_date;
            $movie->title = $request->title;
            $movie->description = $request->description;
            $movie->director = $request->director;

            $movie->save();

            $movie->genres()->attach($request->input('genreIds'));

            return $movie;
        });

		return MovieResource::make($newMovie);
	}

    public function update(UpdateMovieRequest $request, Movie $movie): MovieResource
    {
        $updatedMovie =  DB::transaction(function () use ($request, $movie) {
            if($request->hasFile('image')){
                $movie->image =  config('app.url') . '/storage/' . $request->file('image')->store('images');
            }
            $movie->release_date = $request->release_date;
            $movie->title = $request->title;
            $movie->description = $request->description;
            $movie->director = $request->director;

            $movie->save();
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
