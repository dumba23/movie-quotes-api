<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'             => $this->id,
			'user_id'        => $this->user_id,
			'sender_id'      => $this->sender_id,
			'quote_id'       => $this->quote_id,
			'type'           => $this->type,
			'message'        => $this->message,
			'read'           => $this->read,
		];
	}
}
