<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $user = auth()->user();
        $team = $user?->teams->first();

        return array_merge(parent::share($request), [
            'name' => config('app.name'),
            'user.permissions' => $user?->permissions_names,
            'teamPermissions' => [
                'name' => $team?->name,
                'canDelete' => Gate::check('delete', $team),
                'canUpdate' => Gate::check('update', $team),
            ],
        ]);
    }
}
