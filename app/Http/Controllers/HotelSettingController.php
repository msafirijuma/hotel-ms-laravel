<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelSetting;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogActivity;

class HotelSettingController extends Controller
{
    /**
     * View Page
     */
    public function show()
    {
        $settings = HotelSetting::firstOrCreate(['id' => 1], [
            'hotel_name' => 'Our Hotel',
            'footer_message' => 'Thanks for choosing our hotel.'
        ]);

        return view('settings.show', compact('settings'));
    }

    /**
     * Edit page
     */
    public function edit()
    {
        $settings = HotelSetting::findOrFail(1);
        return view('settings.edit', compact('settings'));
    }

    /**
     * Sava and update settings
     */
    public function update(Request $request)
    {
        $settings = HotelSetting::findOrFail(1);

        $request->validate([
            'hotel_name'     => 'required|string|max:255',
            'tagline'        => 'nullable|string|max:255',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'website'        => 'nullable|string|max:255',
            'tin'            => 'nullable|string|max:50',
            'footer_message' => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['hotel_name', 'tagline', 'address', 'phone', 'email', 'website', 'tin', 'footer_message']);

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::delete('public/' . $settings->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('uploads/settings', 'public');
        }

        $settings->update($data);
        LogActivity::log('Update Settings', "Has updated hotel's setting");

        return redirect()->route('settings.show')->with('success', 'Hotel settings have been updated successfully!');
    }
}
