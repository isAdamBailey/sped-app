<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class SiteSettingController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(): Response
    {
        return Inertia::render('Dashboard/Settings/Show', [
            'settings' => SiteSettingResource::make(SiteSetting::first()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  SiteSetting  $setting
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, SiteSetting $setting): Redirector|RedirectResponse|Application
    {
        $request->validate([
            'registration_active' => 'boolean|nullable',
        ]);

        if (isset($request->registration_active)) {
            $setting->registration_active = $request->registration_active;
        }
        $setting->save();

        return redirect(route('site-settings.show'))
            ->with('flash.banner', 'Settings successfully updated!');
    }
}
