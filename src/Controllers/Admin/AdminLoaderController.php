<?php
namespace Azuriom\Plugin\Centralcorp\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Azuriom\Plugin\Centralcorp\Models\Option;
use Illuminate\Support\Facades\Http;

class AdminLoaderController extends Controller
{
    public function index()
    {
        $options = Option::whereIn('name', [
            'minecraft_version',
            'loader_activation',
            'loader_type',
            'loader_forge_version',
            'loader_build_version',
            'loader_fabric_version',
        ])->pluck('value', 'name');

        return view('centralcorp::admin.loader', compact('options'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'minecraft_version' => 'required|string',
            'loader_activation' => 'required|boolean',
            'loader_type' => 'required|string',
            'loader_forge_version' => 'nullable|string',
            'loader_build_version' => 'nullable|string',
            'loader_fabric_version' => 'nullable|string',
        ]);

        $data = $request->only([
            'minecraft_version',
            'loader_activation',
            'loader_type',
            'loader_forge_version',
            'loader_build_version',
            'loader_fabric_version',
        ]);

        foreach ($data as $name => $value) {
            Option::updateOrCreate(['name' => $name], ['value' => $value]);
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

