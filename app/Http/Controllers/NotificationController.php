<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationMarkAsReadRequest;
use App\Http\Requests\StoreNotificationRequest;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
	public function getNotifications(Notification $notification): JsonResponse
	{
		$notifications = $notification::with('sender')
			->where('user_id', Auth::user()->id)
			->orderBy('created_at', 'desc')
			->get();

		return response()->json($notifications);
	}

	public function markAsRead(NotificationMarkAsReadRequest $request): JsonResponse
	{
		$user = Auth::user();

		$notification = Notification::where('user_id', $user->id)
			->where('id', $request->id)
			->firstOrFail();

		$notification->read = true;
		$notification->save();

		return response()->json(['message' => 'Notification marked as read']);
	}

	public function markAllAsRead(): JsonResponse
	{
		$user = Auth::user();

		$user->notifications()->update(['read' => true]);

		return response()->json(['message' => 'All notifications marked as read']);
	}

	public function store(StoreNotificationRequest $request, Notification $notification): JsonResponse
	{
		$quote = Quote::findOrFail($request->input('quote_id'));

		$notification->user_id = $quote->movie->user->id;
		$notification->quote_id = $request->input('quote_id');
		$notification->sender_id = $request->input('sender_id');
		$notification->type = $request->input('type');
		$notification->message = $request->input('message');
		$notification->save();

		return response()->json(['message' => 'Notification saved successfully']);
	}
}
