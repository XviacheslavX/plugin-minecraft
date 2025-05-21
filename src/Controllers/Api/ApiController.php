<?php

namespace Azuriom\Plugin\Centralcorp\Controllers\Api;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Centralcorp\Models\Option;
use Azuriom\Models\Server;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getOptions()
    {
        $domain = request()->getHost();
        $protocol = request()->isSecure() ? 'https' : 'http';
        $port = request()->getPort();
        $baseURL = $protocol . '://' . $domain . (($port && !in_array($port, [80, 443])) ? ":$port" : '');

        $options = Option::pluck('value', 'name');
        $server = Server::first();

        $loaderType = $options['loader_type'] ?? '';
        $loaderBuild = match ($loaderType) {
            'forge' => $options['loader_forge_version'] ?? '',
            'fabric' => $options['loader_fabric_version'] ?? '',
            default => $options['loader_build_version'] ?? '',
        };

        $data = [
            "maintenance" => (bool) ($options['maintenance'] ?? false),
            "maintenance_message" => $options['maintenance_message'] ?? 'Please define a maintenance message',
            "game_version" => $options['minecraft_version'] ?? '',
            "client_id" => "",
            "verify" => (bool) ($options['file_verification'] ?? false),
            "modde" => (bool) ($options['mods_enabled'] ?? false),
            "java" => (bool) ($options['embedded_java'] ?? false),
            "dataDirectory" => $options['game_folder_name'] ?? '',
            "status" => [
                "nameServer" => $server->name ?? '',
                "ip" => $server->address ?? '',
                "port" => (int) ($server->port ?? 0)
            ],
            "loader" => [
                "type" => $loaderType,
                "build" => $loaderBuild,
                "enable" => (bool) ($options['loader_activation'] ?? false)
            ],
            "ram_min" => (float) ($options['min_ram'] ?? 2048) / 1024,
            "ram_max" => (float) ($options['max_ram'] ?? 4096) / 1024,
            "online" => "true",
            "game_args" => [],
            "money" => (bool) ($options['money_display'] ?? false),
            "role" => (bool) ($options['role_display'] ?? false),
            "splash" => $options['splash'] ?? '',
            "splash_author" => $options['splash_author'] ?? '',
            "rpc_activation" => (bool) ($options['rpc_activation'] ?? false),
            "rpc_id" => $options['rpc_id'] ?? '',
            "rpc_details" => $options['rpc_details'] ?? '',
            "rpc_state" => $options['rpc_state'] ?? '',
            "rpc_large_image" => $options['rpc_large_image'] ?? '',
            "rpc_large_text" => $options['rpc_large_text'] ?? '',
            "rpc_small_image" => $options['rpc_small_image'] ?? '',
            "rpc_small_text" => $options['rpc_small_text'] ?? '',
            "rpc_button1" => $options['rpc_button1'] ?? '',
            "rpc_button1_url" => $options['rpc_button1_url'] ?? '',
            "rpc_button2" => $options['rpc_button2'] ?? '',
            "rpc_button2_url" => $options['rpc_button2_url'] ?? '',
            "whitelist_activate" => (bool) ($options['whitelist'] ?? false),
            "alert_activate" => (bool) ($options['alert_activation'] ?? false),
            "alert_scroll" => (bool) ($options['alert_scroll'] ?? false),
            "alert_msg" => $options['alert_msg'] ?? '',
            "video_activate" => (bool) ($options['video_activation'] ?? false),
            "video_url" => $this->extractYouTubeVideoId($options['video_url'] ?? ''),
            "video_type" => $this->detectVideoType($options['video_url'] ?? ''),
            "email_verified" => (bool) ($options['email_verified'] ?? false),
        ];

        $data["server_icon"] = !empty($server->icon) ? $this->cleanImageUrl($server->icon, $baseURL) : '';

        $roles = DB::table('centralcorp_bg_roles')->get();
        $rolesData = [];

        foreach ($roles as $role) {
            $rolesData["role" . $role->id] = [
                "name" => $role->role_name ?? '',
                "background" => !empty($role->role_background) ? $this->cleanImageUrl($role->role_background, $baseURL) : ""
            ];
        }

        $data["role_data"] = $rolesData;
        $data["ignored"] = DB::table('centralcorp_ignored_folders')->pluck('folder_name');
        $data["whitelist"] = DB::table('centralcorp_whitelist')->pluck('users');
        $data["whitelist_roles"] = DB::table('centralcorp_whitelist_roles')->pluck('role');

        return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function cleanImageUrl($imagePath, $baseURL)
    {
        return $baseURL . '/' . ltrim($imagePath, './');
    }

    private function extractYouTubeVideoId($url)
    {
        if (strpos($url, 'youtube.com/shorts/') !== false) {
            $pattern = '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/';
        } else {
            $pattern = '/(?:https?:\/\/)?(?:www\.)?youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.*v=|v=)?([a-zA-Z0-9_-]{11})/';
        }
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? "";
    }

    private function detectVideoType($url)
    {
        return strpos($url, 'youtube.com/shorts/') !== false ? 'short' : 'normal';
    }
}
