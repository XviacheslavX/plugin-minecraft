<?php
namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Azuriom\Models\Server;
use Illuminate\Support\Facades\Http;
use Azuriom\Plugin\Centralcorp\Models\OptionsLoader;

class AdminLoaderController extends Controller
{
public function index()
{
    $servers = Server::all();
    $options = OptionsLoader::all()->keyBy('server_id');

    return view('centralcorp::admin.loader', compact('servers', 'options'));
}

public function update(Request $request)
{
    $rules = [
        'servers' => ['required', 'array'],
    ];

    foreach ($request->input('servers', []) as $serverId => $data) {
        $rules["servers.$serverId.minecraft_version"] = ['required', 'string', 'max:50'];
        $rules["servers.$serverId.loader_activation"] = ['nullable', 'boolean'];
        $rules["servers.$serverId.loader_type"] = ['required', 'in:forge,fabric,legacyfabric,neoForge,quilt'];

        if (isset($data['loader_type'])) {
            if ($data['loader_type'] === 'forge') {
                $rules["servers.$serverId.loader_forge_version"] = ['required', 'string', 'max:50'];
            } else {
                $rules["servers.$serverId.loader_forge_version"] = ['nullable'];
            }

            if ($data['loader_type'] === 'fabric') {
                $rules["servers.$serverId.loader_fabric_version"] = ['required', 'string', 'max:50'];
            } else {
                $rules["servers.$serverId.loader_fabric_version"] = ['nullable'];
            }

            if (in_array($data['loader_type'], ['legacyfabric', 'neoForge', 'quilt'])) {
                $rules["servers.$serverId.loader_build_version"] = ['required', 'string', 'max:50'];
            } else {
                $rules["servers.$serverId.loader_build_version"] = ['nullable'];
            }
        }
    }

    $validated = $request->validate($rules);

    foreach ($validated['servers'] as $serverId => $data) {
        OptionsLoader::updateOrCreate(
            ['server_id' => $serverId],
            [
                'minecraft_version' => $data['minecraft_version'],
                'loader_activation' => isset($data['loader_activation']) && $data['loader_activation'] ? 1 : 0,
                'loader_type' => $data['loader_type'],
                'loader_forge_version' => $data['loader_forge_version'] ?? null,
                'loader_fabric_version' => $data['loader_fabric_version'] ?? null,
                'loader_build_version' => $data['loader_build_version'] ?? null,
            ]
        );
    }

    return redirect()->back()->with('success', trans('centralcorp::messages.success_loader_update'));
}

    public function getForgeBuilds(Request $request)
    {
        $mcVersion = $request->query('mc_version');
        $url = "https://files.minecraftforge.net/net/minecraftforge/forge/index_$mcVersion.html";
        $response = Http::get($url);
        $builds = [];

        if ($response->successful()) {
            $html = $response->body();
            $dom = new \DOMDocument;
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            $xpath = new \DOMXPath($dom);

            $links = $xpath->query('//a[contains(@href, "maven.minecraftforge.net/net/minecraftforge/forge/")]');

            foreach ($links as $link) {
                $href = $link->getAttribute('href');

                if (preg_match('/forge\/([\d\.\-]+)\/forge-\1-/', $href, $matches)) {
                    $version = $matches[1];

                    if (!in_array($version, $builds)) {
                        $builds[] = $version;
                    }
                }
            }
        }

        return response()->json(['builds' => $builds]);
    }

    public function getFabricVersions()
    {
        $url = 'https://meta.fabricmc.net/v2/versions/loader';
        $response = Http::get($url);
        $versions = [];

        if ($response->successful()) {
            $versions = $response->json();
        }

        return response()->json(['versions' => $versions]);
    }
}

