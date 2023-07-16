<?php

namespace App\Services;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;
class MovieService
{
    public function createMovie(StoreMovieRequest $request, Movie $movie): Movie
    {
        return DB::transaction(function () use ($movie, $request) {
            $movie->image =  config('app.url') . '/storage/' . $request->file('image')->store('images');
            $movie->user_id = auth()->id();
            $movie->release_date = $request->release_date;

            $movie->setTranslations('title', [
                'en' => $request->title_en,
                'ka' => $request->title_ka,
            ]);

            $movie->setTranslations('description', [
                'en' => $request->description_en,
                'ka' => $request->description_ka,
            ]);

            $movie->setTranslations('director', [
                'en' => $request->director_en,
                'ka' => $request->director_ka,
            ]);

            $movie->save();

            $movie->genres()->attach($request->input('genreIds'));

            return $movie;
        });
    }

    public function updateMovie(UpdateMovieRequest $request, Movie $movie): Movie
    {
        return DB::transaction(function () use ($request, $movie) {
            if($request->hasFile('image')){
                $movie->image =  config('app.url') . '/storage/' . $request->file('image')->store('images');
            }
            $movie->release_date = $request->release_date;

            $movie->setTranslations('title', [
                'en' => $request->title_en,
                'ka' => $request->title_ka,
            ]);

            $movie->setTranslations('description', [
                'en' => $request->description_en,
                'ka' => $request->description_ka,
            ]);

            $movie->setTranslations('director', [
                'en' => $request->director_en,
                'ka' => $request->director_ka,
            ]);

            $movie->save();

            $movie->genres()->detach();

            $movie->genres()->attach($request->input('genreIds'));

            return $movie;
        });
    }

    public function deleteMovie(string $movieId): void
    {
        $movie = Movie::findOrFail($movieId);
        $movie->delete();
    }
}
