<?php

namespace Azuriom\Plugin\centralcorp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Azuriom\Models\Server;

class OptionsServer extends Model
{
    use HasFactory;

    protected $table = 'centralcorp_server_options';

    protected $fillable = [
        'server_id',
        'icon'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}