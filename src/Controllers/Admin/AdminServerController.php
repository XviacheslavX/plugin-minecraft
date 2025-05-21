<?php
namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Centralcorp\Models\OptionsServer;
use Azuriom\Models\Server;
use Illuminate\Http\Request;

class AdminServerController extends Controller
{
    public function show()
    {
        $serverOptions = OptionsServer::first();
        $servers = Server::all();
        $currentServer = null;

        if ($serverOptions) {
            $currentServer = Server::where('name', $serverOptions->server_name)
                ->where('address', $serverOptions->server_ip)
                ->where('port', $serverOptions->server_port)
                ->first();
        }

        return view('centralcorp::admin.server', compact('serverOptions', 'servers', 'currentServer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'server_id' => 'required|exists:servers,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $serverOptions = OptionsServer::first();

        if (!$serverOptions) {
            $serverOptions = new OptionsServer();
        }

        $selectedServer = Server::find($request->server_id);

        if ($request->hasFile('icon')) {
            if ($serverOptions->icon) {
                \Storage::disk('public')->delete(str_replace('/storage/', '', $serverOptions->icon));
            }

            $path = $request->file('icon')->store('server_icon', 'public');
            $serverOptions->icon = '/storage/' . $path;

            $selectedServer->icon = $path;
            $selectedServer->save();
        }
        $serverOptions->save();

        return redirect()->route('centralcorp.admin.server')->with('success', trans('centralcorp::messages.success_server_updated'));
    }
}


