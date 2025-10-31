<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function alert()
    {
        $setting = Setting::first();
        return view('settings.alert', compact('setting'));
    }

    public function updateAlert(Request $request)
    {
        $data = $request->validate([
            'alert_title' => 'nullable|string|max:255',
            'alert_message' => 'nullable|string',
        ]);

        $setting = Setting::first();
        if ($setting) {
            $setting->update($data);
        } else {
            Setting::create($data);
        }

        return redirect()->route('alert.index')->with('success', 'アラート設定を更新しました。');
    }
}
