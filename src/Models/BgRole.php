<?php

namespace Azuriom\Plugin\centralcorp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BgRole extends Model
{
    use HasFactory;

    protected $table = 'centralcorp_bg_roles';

    protected $fillable = [
        'role_name',
        'role_background',
    ];

    public $timestamps = true;
}
