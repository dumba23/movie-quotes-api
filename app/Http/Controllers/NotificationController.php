<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationMarkAsReadRequest;
use App\Http\Requests\StoreNotificationRequest;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications(Request $request, Notification $notification): JsonResponse
    {
        $perPage = $request->input('per_page', 5);
        $currentPage = $request->input('page', 1);

        $query = $notification::with('sender')
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc');

        $paginator = $query->paginate($perPage, ['*'], 'page', $currentPage);

        $paginationData = [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];

        $data = [
            'notifications' => $paginator->items(),
            'pagination' => $paginationData,
        ];

        return response()->json($data);
    }

    public function markSelectedAsRead(Request $request): JsonResponse
    {
        $user = Auth::user();
        $notificationIds = $request->input('id', []);

        $user->notifications()
            ->whereIn('id', $notificationIds)
            ->update(['read' => true]);

        return response()->json(['message' => 'Selected notifications marked as read']);
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

        if($quote->movie->user->id === $request->input('sender_id')) {
            return response()->json(['message' => 'Nothing to store']);
        }

        $notification->user_id = $quote->movie->user->id;
        $notification->quote_id = $request->input('quote_id');
        $notification->sender_id = $request->input('sender_id');
        $notification->type = $request->input('type');
        $notification->message = $request->input('message');
        $notification->save();

        return response()->json(['message' => 'Notification saved successfully']);
    }
}
