<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->search;
        $filter = $request->filter;

        $users = User::query()
            ->when(
                $search,
                fn ($query) => $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%')
            )
            ->when($filter, function ($query) use ($filter) {
                if (Str::startsWith($filter, 'team_')) {
                    return $query->with('teams')->whereHas('teams',
                        fn ($q) => $q->where('team_user.role', Str::replace('team_', '', $filter)));
                }

                return $query->whereHas(
                    'permissions',
                    fn ($q) => $q->with('permissions')->where('permissions.name', Str::replace('_', ' ', $filter))
                );
            })
            ->paginate();

        return Inertia::render('Dashboard/Users/Index', [
            'users' => UserResource::collection($users),
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    public function show(User $user): Response
    {
        $userObject = $user
            ->only('id', 'name', 'email', 'profile_photo_url', 'teams', 'permissions_names');

        return Inertia::render('Dashboard/Users/Show', [
            'userObject' => $userObject,
            'permissions' => Permission::all()->pluck('name'),
        ]);
    }

    public function update(Request $request, User $user): Redirector|Application|RedirectResponse
    {
        $request->validate([
            'permissions' => 'array|nullable',
        ]);

        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect(route('users.show', $user->fresh()))
            ->with('flash.banner', 'Permissions successfully updated!');
    }
}
