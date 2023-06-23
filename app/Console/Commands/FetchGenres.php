<?php

namespace App\Console\Commands;

use App\Models\Genre;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchGenres extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:genres';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches genres from themoviedb';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://api.themoviedb.org/3/genre/movie/list', [
            'api_key' => env('GENRE_API_KEY'),
            'language' => 'en-US',
        ]);

        $genres = $response->json()['genres'];

        foreach ($genres as $genre) {
            Genre::updateOrCreate(['name' => $genre['name']]);
        }

        $this->info('Genres updated successfully.');
    }
}
