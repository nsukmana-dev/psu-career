<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LandingSettingType;
use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.landing-settings.edit', [
            'settings' => LandingSetting::all()->keyBy('key'),
        ]);
    }

    public function update(Request $request, ImageUploadService $images): RedirectResponse
    {
        $settings = LandingSetting::all();

        $request->validate(
            $settings->where('type', LandingSettingType::Image)
                ->mapWithKeys(fn (LandingSetting $setting) => [$setting->key => ['nullable', 'image', 'max:2048']])
                ->all()
        );

        foreach ($settings as $setting) {
            if ($setting->type === LandingSettingType::Image) {
                if ($request->hasFile($setting->key)) {
                    $setting->update([
                        'value' => $images->storeResized($request->file($setting->key), 'public', 'landing', 600, 600),
                    ]);
                }

                continue;
            }

            if ($request->has($setting->key)) {
                $setting->update(['value' => $request->input($setting->key)]);
            }
        }

        return back()->with('status', 'Pengaturan landing page tersimpan.');
    }
}
