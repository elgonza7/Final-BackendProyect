<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAllUsers(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        
        $users = User::with(['roles', 'permissions', 'posts', 'comments'])
            ->withCount(['posts', 'comments', 'activities'])
            ->paginate($perPage);

        return response()->json($users, 200);
    }
    public function getUser($id)
    {
        $user = User::with(['roles', 'permissions', 'posts', 'comments'])
            ->withCount(['posts', 'comments', 'activities'])
            ->findOrFail($id);

        return response()->json([
            'user' => $user,
        ], 200);
    }
    public function getAllActivities(Request $request)
    {
        $perPage = $request->input('per_page', 50);
        $userId = $request->input('user_id');
        $activityType = $request->input('activity_type');

        $query = UserActivity::with('user');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($activityType) {
            $query->where('activity_type', $activityType);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($activities, 200);
    }
    public function getUserActivities($userId, Request $request)
    {
        $perPage = $request->input('per_page', 50);

        $user = User::findOrFail($userId);

        $activities = UserActivity::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'user' => $user,
            'activities' => $activities,
        ], 200);
    }
    public function getStatistics()
    {
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $totalActivities = UserActivity::count();
        
        $recentActivities = UserActivity::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $activitiesByType = UserActivity::selectRaw('activity_type, COUNT(*) as count')
            ->groupBy('activity_type')
            ->get();

        return response()->json([
            'total_users' => $totalUsers,
            'verified_users' => $verifiedUsers,
            'total_activities' => $totalActivities,
            'recent_activities' => $recentActivities,
            'activities_by_type' => $activitiesByType,
        ], 200);
    }

    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::findOrFail($userId);
        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Rol asignado exitosamente',
            'user' => $user->load('roles')
        ], 200);
    }

    public function removeRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::findOrFail($userId);
        $user->removeRole($request->role);

        return response()->json([
            'message' => 'Rol removido exitosamente',
            'user' => $user->load('roles')
        ], 200);
    }
    public function givePermission(Request $request, $userId)
    {
        $request->validate([
            'permission' => 'required|string|exists:permissions,name'
        ]);

        $user = User::findOrFail($userId);
        $user->givePermissionTo($request->permission);

        return response()->json([
            'message' => 'Permiso otorgado exitosamente',
            'user' => $user->load('permissions')
        ], 200);
    }

    public function revokePermission(Request $request, $userId)
    {
        $request->validate([
            'permission' => 'required|string|exists:permissions,name'
        ]);

        $user = User::findOrFail($userId);
        $user->revokePermissionTo($request->permission);

        return response()->json([
            'message' => 'Permiso revocado exitosamente',
            'user' => $user->load('permissions')
        ], 200);
    }
}
