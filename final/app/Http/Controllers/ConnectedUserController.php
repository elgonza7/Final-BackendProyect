<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;

class ConnectedUserController extends Controller
{
    public function index()
    {
        $userIds = UserActivity::query()
            ->where('activity_type', 'presence_ping')
            ->where('updated_at', '>=', now()->subMinutes(5))
            ->pluck('user_id');

        $connectedUsers = User::query()
            ->whereIn('id', $userIds)
            ->select('id', 'name', 'avatar')
            ->orderBy('name')
            ->get();

        return response()->json([
            'connected_users' => $connectedUsers,
        ]);
    }
}
