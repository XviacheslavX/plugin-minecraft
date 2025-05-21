<?php
namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Role;
use Azuriom\Plugin\Centralcorp\Models\BgRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminRoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $rolesWithBackgrounds = $roles->map(function ($role) {
            $bgRole = BgRole::where('role_name', $role->name)->first();
            $role->role_background = $bgRole ? $bgRole->role_background : null;
            return $role;
        });

        return view('centralcorp::admin.roles', [
            'roles' => $rolesWithBackgrounds
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_select' => 'required|exists:roles,id',
            "role{$request->input('role_select')}_background" => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:min_width=1920,min_height=1080',
        ], [
            'role_select.required' => trans('centralcorp::messages.role_select_required'),
            'role_select.exists' => trans('centralcorp::messages.role_select_exists'),
            "role{$request->input('role_select')}_background.image" => trans('centralcorp::messages.role_background_image'),
            "role{$request->input('role_select')}_background.mimes" => trans('centralcorp::messages.role_background_mimes'),
            "role{$request->input('role_select')}_background.max" => trans('centralcorp::messages.role_background_max'),
            "role{$request->input('role_select')}_background.dimensions" => trans('centralcorp::messages.role_background_dimensions'),
        ]);

        if ($validator->fails()) {
            return redirect()->route('centralcorp.admin.roles.index')
                ->withErrors($validator)
                ->withInput();
        }

        $roleId = $request->input('role_select');
        $role = Role::find($roleId);

        if ($role) {
            if ($request->hasFile("role{$roleId}_background")) {
                $file = $request->file("role{$roleId}_background");

                if ($file->isValid()) {
                    $bgRole = BgRole::where('role_name', $role->name)->first();

                    if ($bgRole && $bgRole->role_background) {
                        Storage::disk('public')->delete(str_replace('/storage/', '', $bgRole->role_background));
                    }

                    $imagePath = $file->store('role_backgrounds', 'public');

                    BgRole::updateOrCreate(
                        ['role_name' => $role->name],
                        ['role_background' => '/storage/' . $imagePath]
                    );
                }
            }

            return redirect()->route('centralcorp.admin.roles.index')->with('success', trans('centralcorp::messages.success_role_updated'));
        }

        return redirect()->route('centralcorp.admin.roles.index')->with('error', trans('centralcorp::messages.error_role_not_found'));
    }

    public function cleanUp()
    {
        $rolesInBgRoles = BgRole::all();
        $existingRoles = Role::pluck('name')->toArray();

        foreach ($rolesInBgRoles as $bgRole) {
            if (!in_array($bgRole->role_name, $existingRoles)) {
                if ($bgRole->role_background) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $bgRole->role_background));
                }

                $bgRole->delete();
            }
        }

        return redirect()->route('centralcorp.admin.roles.index')->with('success', trans('centralcorp::messages.success_roles_cleaned'));
    }
}
