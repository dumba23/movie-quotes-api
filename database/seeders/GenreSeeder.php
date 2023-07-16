<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ["id" => 1, "name" => "Action"],
            ["id" => 2, "name" => "Adventure"],
            ["id" => 3, "name" => "Animation"],
            ["id" => 4, "name" => "Comedy"],
            ["id" => 5, "name" => "Crime"],
            ["id" => 6, "name" => "Documentary"],
            ["id" => 7, "name" => "Drama"],
            ["id" => 8, "name" => "Family"],
            ["id" => 9, "name" => "Fantasy"],
            ["id" => 10, "name" => "History"],
            ["id" => 11, "name" => "Horror"],
            ["id" => 12, "name" => "Music"],
            ["id" => 13, "name" => "Mystery"],
            ["id" => 14, "name" => "Romance"],
            ["id" => 15, "name" => "Science Fiction"],
            ["id" => 16, "name" => "TV Movie"],
            ["id" => 17, "name" => "Thriller"],
            ["id" => 18, "name" => "War"],
            ["id" => 19, "name" => "Western"],
        ];

        Genre::insert($genres);
    }
}
