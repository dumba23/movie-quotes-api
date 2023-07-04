<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCommentEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public Comment $comment;

	public User $user;

	public Quote $quote;

	/**
	 * Create a new event instance.
	 */
	public function __construct(Comment $comment, User $user, Quote $quote)
	{
		$this->comment = $comment;
		$this->user = $user;
		$this->quote = $quote;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array<int, \Illuminate\Broadcasting\Channel>
	 */
	public function broadcastOn(): array
	{
		return [
			new PrivateChannel('post.' . $this->user->id),
		];
	}
}