<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'    => $this->id,
			'title' => [
				'en' => $this->getTranslation('title', 'en'),
				'ka' => $this->getTranslation('title', 'ka'),
			],
			'image'      => $this->image,
			'movie_id'   => $this->movie_id,
			'movie'      => $this->movie,
			'comments'   => CommentResource::collection($this->comments),
			'likes'      => $this->likes,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
