<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->search;
        $filter = $request->filter;

        $users = User::with(['roles', 'permissions'])
            ->when(
                $search,
                fn ($query) => $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%')
            )
            ->when($filter, function ($query) use ($filter) {
                if (Str::startsWith($filter, 'team_')) {
                    return $query->whereHas('teams',
                        fn ($q) => $q->where('team_user.role', Str::replace('team_', '', $filter)));
                }

                return $query->whereHas(
                    'roles',
                    fn ($q) => $q->where('roles.name', Str::replace('_', ' ', $filter))
                );
            })
            ->paginate();

        return Inertia::render('Dashboard/Users/Index', [
            'users' => $users->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo_url' => $user->profile_photo_url,
                'teams' => $user->teams,
                'roles' => $user->roles,
                'permissions' => $user->permissions,
            ]),
            'search' => $search,
            'filter' => $filter,
        ]);
    }
}
