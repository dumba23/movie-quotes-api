<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexNotificationRequest;
use App\Http\Requests\MarkSelectedAsReadRequest;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
	public function index(IndexNotificationRequest $request, Notification $notification): JsonResponse
	{
		$perPage = $request->input('per_page', 5);
		$currentPage = $request->input('page', 1);

		$query = $notification::with('sender')
			->where('user_id', Auth::user()->id)
			->orderBy('created_at', 'desc');

		$paginator = $query->paginate($perPage, ['*'], 'page', $currentPage);

		$paginationData = [
			'current_page' => $paginator->currentPage(),
			'last_page'    => $paginator->lastPage(),
			'per_page'     => $paginator->perPage(),
			'total'        => $paginator->total(),
		];

		$data = [
			'notifications' => NotificationResource::collection($paginator->items()),
			'pagination'    => $paginationData,
		];

		return response()->json($data);
	}

	public function markSelectedAsRead(MarkSelectedAsReadRequest $request): JsonResponse
	{
		$user = Auth::user();
		$notificationIds = $request->id;

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

	public function store(StoreNotificationRequest $request): JsonResponse
	{
		$quote = Quote::findOrFail($request->quote_id);

		if ($quote->movie->user->id === $request->sender_id) {
			return response()->json(['message' => 'Nothing to store']);
		}

		Notification::create($request->validated() + [
			'user_id' => $quote->movie->user->id,
		]);

		return response()->json(['message' => 'Notification saved successfully']);
	}
}
