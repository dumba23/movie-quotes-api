<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;

class MoviePolicy
{
	/**
	 * Determine whether the user can view the movie.
	 *
	 * @param \App\Models\User  $user
	 * @param \App\Models\Movie $movie
	 *
	 * @return bool
	 */
	public function get(User $user, Movie $movie): bool
	{
		return $user->id === $movie->user_id;
	}
}
