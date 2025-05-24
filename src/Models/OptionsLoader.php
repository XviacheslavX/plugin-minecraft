<?php

namespace Azuriom\Plugin\centralcorp\Models;

use Illuminate\Database\Eloquent\Model;
use Azuriom\Models\Server;

class OptionsLoader extends Model
{
    protected $fillable = [
        'server_id',
        'minecraft_version',
        'loader_activation',
        'loader_type',
        'loader_forge_version',
        'loader_fabric_version',
        'loader_build_version',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}