<?php

namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Centralcorp\Models\Option;
use Illuminate\Http\Request;

class AdminUIController extends Controller
{
    public function show()
    {
        $options = Option::pluck('value', 'name');
        return view('centralcorp::admin.ui', compact('options'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'alert_activation' => 'boolean',
            'alert_scroll' => 'boolean',
            'alert_msg' => 'nullable|string|max:255',
            'video_activation' => 'boolean',
            'video_url' => 'nullable|string|max:255',
            'splash' => 'required|string|max:255',
            'splash_author' => 'required|string|max:255',
        ]);

        $data = $request->only([
            'alert_activation',
            'alert_scroll',
            'alert_msg',
            'video_activation',
            'video_url',
            'splash',
            'splash_author',
        ]);

        foreach ($data as $name => $value) {
            Option::updateOrCreate(['name' => $name], ['value' => $value]);
        }

        return redirect()->route('centralcorp.admin.ui')->with('success', trans('centralcorp::messages.success_ui_update'));
    }
}
