<?php

namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Azuriom\Plugin\Centralcorp\Models\Option;

class AdminController extends Controller
{
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mods_enabled' => 'boolean',
            'file_verification' => 'boolean',
            'embedded_java' => 'boolean',
            'game_folder_name' => 'required|string|max:100',
            'email_verified' => 'boolean',
            'role_display' => 'nullable|integer',
            'money_display' => 'nullable|integer',
            'min_ram' => 'required|integer|min:512|max:65536',
            'max_ram' => 'required|integer|min:512|max:65536',
        ]);

        if ($validator->fails()) {
            return redirect()->route('centralcorp.admin.general')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'mods_enabled',
            'file_verification',
            'embedded_java',
            'game_folder_name',
            'email_verified',
            'role_display',
            'money_display',
            'min_ram',
            'max_ram',
        ]);

        foreach ($data as $name => $value) {
            Option::updateOrCreate(['name' => $name], ['value' => $value]);
        }

        return redirect()->route('centralcorp.admin.general')->with('success', trans('centralcorp::messages.success_update'));
    }

    public function general()
    {
        $options = Option::pluck('value', 'name');
        return view('centralcorp::admin.general', compact('options'));
    }
}
