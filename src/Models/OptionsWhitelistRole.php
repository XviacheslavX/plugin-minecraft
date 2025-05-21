<?php
namespace Azuriom\Plugin\centralcorp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsWhitelistRole extends Model
{
    use HasFactory;

    protected $table = 'centralcorp_whitelist_roles';
    protected $fillable = ['role'];
}

