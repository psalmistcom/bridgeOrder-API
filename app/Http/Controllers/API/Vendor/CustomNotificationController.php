<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\NotificationResource;
use App\Models\Utility\CustomNotification;
use App\Models\Vendor\Restaurant;
use Illuminate\Http\Request;

class CustomNotificationController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse(
            NotificationResource::collection($request->user()->restaurant->customNotifications),
            'All restaurant notifications fetched successfully'
        );
    }

    public function unread(Request $request): \Illuminate\Http\JsonResponse
    {
        $notification = CustomNotification::whereNotificableType(Restaurant::class)
            ->whereNotificableId($request->user()->restaurant->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
        return $this->successResponse(
            NotificationResource::collection($notification),
            'Unread restaurant notifications fetched successfully'
        );
    }

    public function read(Request $request): \Illuminate\Http\JsonResponse
    {
        $notification = CustomNotification::whereNotificableType(Restaurant::class)
            ->whereNotificableId($request->user()->restaurant->id)
            ->whereNotNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
        return $this->successResponse(
            NotificationResource::collection($notification),
            'Read restaurant notifications fetched successfully'
        );
    }

    public function view(Request $request, CustomNotification $customNotification): \Illuminate\Http\JsonResponse
    {
        $customNotification->update(['read_at' => now()]);

        return $this->successResponse(
            NotificationResource::make($customNotification),
            'Restaurant notification viewed successfully'
        );
    }
}
