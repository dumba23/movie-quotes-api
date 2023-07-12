<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request)
	{
		return [
			'id'           => $this->id,
			'title'        => $this->getTranslations('title'),
			'director'     => $this->getTranslations('director'),
			'description'  => $this->getTranslations('description'),
			'release_date' => $this->release_date,
			'genre'        => $this->whenLoaded('genres', GenreResource::collection($this->genres)),
			'image'        => $this->image,
			'quotes'       => $this->quotes->load(['comments', 'likes']),
		];
	}
}
