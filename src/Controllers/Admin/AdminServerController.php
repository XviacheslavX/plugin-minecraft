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
        $servers = Server::all();
        $serverOptions = OptionsServer::all()->keyBy('server_id');
        return view('centralcorp::admin.server', compact('servers', 'serverOptions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'servers.*.server_id' => 'required|exists:servers,id',
            'servers.*.icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        foreach ($request->input('servers') as $index => $serverData) {
            $serverId = $serverData['server_id'];
            $options = OptionsServer::firstOrNew(['server_id' => $serverId]);

            if ($request->hasFile("servers.$index.icon")) {
                if ($options->icon) {
                    \Storage::disk('public')->delete(str_replace('/storage/', '', $options->icon));
                }

                $path = $request->file("servers.$index.icon")->store('server_icon', 'public');
                $options->icon = '/storage/' . $path;
            }

            $options->save();
        }

        return redirect()->route('centralcorp.admin.server')->with('success', 'Сервери оновлено');
    }
}


