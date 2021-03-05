<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();

        return view('settings', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'min_proposals' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect(route('setting.index'))->withErrors($validator->errors());
        }

        $settings = $request->all();

        foreach ($settings as $key => $value) {
            if (in_array($key, Setting::$possibleKeys)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        }

        return redirect(route('setting.index'));
    }
}
