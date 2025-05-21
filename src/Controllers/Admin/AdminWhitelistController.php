<?php

namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Centralcorp\Models\Option;
use Azuriom\Plugin\Centralcorp\Models\OptionsWhitelist;
use Azuriom\Plugin\Centralcorp\Models\OptionsWhitelistRole;
use Azuriom\Models\Role;
use Azuriom\Models\User;
use Illuminate\Http\Request;

class AdminWhitelistController extends Controller
{
    public function index()
    {
        $whitelistedUsers = OptionsWhitelist::all()->pluck('users');
        $whitelistedRoles = OptionsWhitelistRole::all()->pluck('role');

        $allUsers = User::whereNull('deleted_at')
            ->whereNotIn('name', $whitelistedUsers)
            ->get();

        $allRoles = Role::whereNotIn('name', $whitelistedRoles)->get();

        $whitelistEnabled = Option::where('name', 'whitelist')->value('value') ?? false;

        return view('centralcorp::admin.whitelist', [
            'whitelistedUsers' => OptionsWhitelist::all(),
            'whitelistedRoles' => OptionsWhitelistRole::all(),
            'allUsers' => $allUsers,
            'allRoles' => $allRoles,
            'whitelistEnabled' => filter_var($whitelistEnabled, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function store(Request $request)
    {
        $whitelistActivation = $request->boolean('whitelist');

        Option::updateOrCreate(['name' => 'whitelist'], ['value' => $whitelistActivation]);

        if ($request->has('whitelist_users')) {
            $userIds = $request->input('whitelist_users');

            foreach ($userIds as $userId) {
                $user = User::find($userId);
                if ($user) {
                    OptionsWhitelist::create(['users' => $user->name]);
                }
            }
        }

        if ($request->has('whitelist_roles')) {
            $roleIds = $request->input('whitelist_roles');

            foreach ($roleIds as $roleId) {
                $role = Role::find($roleId);
                if ($role) {
                    OptionsWhitelistRole::create(['role' => $role->name]);
                }
            }
        }

        return redirect()->route('centralcorp.admin.whitelist')->with('success', trans('centralcorp::messages.success_whitelist_update'));
    }

    public function destroyUser($id)
    {
        OptionsWhitelist::findOrFail($id)->delete();
        return redirect()->route('centralcorp.admin.whitelist')->with('success', trans('centralcorp::messages.success_user_removed'));
    }

    public function destroyRole($id)
    {
        OptionsWhitelistRole::findOrFail($id)->delete();
        return redirect()->route('centralcorp.admin.whitelist')->with('success', trans('centralcorp::messages.success_role_removed'));
    }
}
