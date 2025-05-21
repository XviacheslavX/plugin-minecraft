<?php

namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Centralcorp\Models\OptionsIgnore;
use Illuminate\Http\Request;

class AdminIgnoreController extends Controller
{
    public function index()
    {
        $folders = OptionsIgnore::all();

        $ignoreOptions = OptionsIgnore::first();
        return view('centralcorp::admin.ignore', compact('folders', 'ignoreOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ignored_folders' => 'required|string|max:255',
        ]);

        $ignoreOptions = OptionsIgnore::first();

        if ($ignoreOptions) {
            $ignoreOptions->save();
        }

        if ($request->input('ignored_folders')) {
            $folders = explode(',', $request->input('ignored_folders'));
            foreach ($folders as $folder) {
                OptionsIgnore::create(['folder_name' => trim($folder)]);
            }
        }

        return redirect()->route('centralcorp.admin.ignore')->with('success', trans('centralcorp::messages.success_ignore_update'));
    }

    public function destroyFolder($id)
    {
        OptionsIgnore::findOrFail($id)->delete();
        return redirect()->route('centralcorp.admin.ignore')->with('success', trans('centralcorp::messages.success_folder_removed'));
    }
}
