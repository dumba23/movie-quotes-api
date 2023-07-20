<?php

namespace App\Events;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLikeEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Create a new event instance.
	 */
	public function __construct(public Quote $quote, public User $user, public bool $like)
	{
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array<int, \Illuminate\Broadcasting\Channel>
	 */
	public function broadcastOn(): array
	{
		return [
			new Channel('like'),
		];
	}
}
