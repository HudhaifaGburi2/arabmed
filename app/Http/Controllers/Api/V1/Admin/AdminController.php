<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Course, Video, Exam};
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 15);
        $q = $request->string('q')->toString();
        $sortBy = $request->string('sort_by', 'id')->toString();
        $sortDir = strtolower($request->string('sort_dir', 'desc')->toString()) === 'asc' ? 'asc' : 'desc';
        $allowed = ['id','first_name','last_name','email','created_at'];
        if (! in_array($sortBy, $allowed, true)) {
            $sortBy = 'id';
        }
        $users = User::query()
            ->with('roles')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($x) use ($q) {
                    $x->where('first_name', 'like', "%$q%")
                        ->orWhere('last_name', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%");
                });
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage);

        return response()->json($users);
    }

    public function stats()
    {
        return response()->json([
            'users' => [
                'total' => User::count(),
                'by_role' => User::selectRaw('roles.name as role, count(users.id) as count')
                    ->join('user_role', 'user_role.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'user_role.role_id')
                    ->groupBy('roles.name')
                    ->pluck('count', 'role'),
            ],
            'courses' => [
                'total' => Course::count(),
                'published' => Course::where('is_published', true)->count(),
            ],
            'videos' => [
                'total' => Video::count(),
                'published' => Video::where('is_published', true)->count(),
            ],
            'exams' => [
                'total' => Exam::count(),
                'active' => Exam::where('is_active', true)->count(),
            ],
        ]);
    }
}
