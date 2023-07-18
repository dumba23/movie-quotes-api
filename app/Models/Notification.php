<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
	use HasFactory;

	protected $fillable = [
		'sender_id',
		'user_id',
		'quote_id',
		'type',
		'message',
		'read',
	];

	public $timestamps = true;

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function sender()
	{
		return $this->belongsTo(User::class, 'sender_id');
	}
}
