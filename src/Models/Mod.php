<?php
namespace Azuriom\Plugin\centralcorp\Models;
use Illuminate\Database\Eloquent\Model;

class Mod extends Model
{
    protected $table = 'centralcorp_mods';

    protected $fillable = [
        'file',
        'name',
        'description',
        'icon',
        'optional',
        'recommended'
    ];
}
