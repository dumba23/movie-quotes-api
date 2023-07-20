<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
	use HasFactory;

	use HasTranslations;

	public array $translatable = ['title', 'description', 'director'];

	protected $fillable = [
		'title',
		'description',
		'director',
		'image',
		'release_date',
		'user_id',
	];

	public function genres(): BelongsToMany
	{
		return $this->belongsToMany(Genre::class, 'movie_genres', 'movie_id', 'genre_id');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class);
	}
}
