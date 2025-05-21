<?php

namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Azuriom\Plugin\Centralcorp\Models\OptionsMods;
use Illuminate\Support\Facades\Storage;

class AdminModController extends Controller
{
    public function index(Request $request)
    {
        $modsDir = storage_path('app/public/data/mods');

        $jarFiles = glob($modsDir . '/*.jar');
        $modsData = [];

        foreach ($jarFiles as $index => $jarFile) {
            $modsData[] = [
                'file' => basename($jarFile),
                'name' => basename($jarFile),
                'description' => '',
                'icon' => '',
                'optional' => 0,
            ];
        }
        $optionalMods = OptionsMods::where('optional', 1)->get();
        $selectedModId = $request->input('selectedMod', null);

        return view('centralcorp::admin.mods', compact('modsData', 'optionalMods', 'selectedModId'));
    }


    public function updateOptionalMod(Request $request)
    {
        $mod = OptionsMods::findOrFail($request->mod_id);
        $mod->name = $request->optional_name;
        $mod->description = $request->optional_description;
        $mod->recommended = $request->has('optional_recommended') ? 1 : 0;

        if ($request->hasFile('optional_image')) {
            if ($mod->icon && Storage::disk('public')->exists($mod->icon)) {
                Storage::disk('public')->delete($mod->icon);
            }
            $mod->icon = $request->file('optional_image')->store('mods_icons', 'public');
        }

        $mod->save();

        return redirect()->back()->with('success', trans('centralcorp::messages.success_mod_updated'));
    }
    public function deleteOptionalMod($id)
    {
        try {
            $mod = OptionsMods::findOrFail($id);
            $mod->delete();

            return redirect()->back()->with('success', trans('centralcorp::messages.success_mod_removed'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', trans('centralcorp::messages.error_mod_deletion') . $e->getMessage());
        }
    }

    public function addOptionalMod(Request $request)
    {
        $mod = new OptionsMods();
        $mod->file = $request->file;
        $mod->name = $request->name;
        $mod->description = $request->description;
        $mod->optional = 1;
        $mod->save();

        return redirect()->back()->with('success', trans('centralcorp::messages.success_mod_added'));
    }
    public function getOptionalModDetails($id)
    {
        $mod = OptionsMods::find($id);
        if (!$mod) {
            return response()->json(['error' => trans('centralcorp::messages.error_mod_not_found')], 404);
        }
        return response()->json($mod);
    }


}
