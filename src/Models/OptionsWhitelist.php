<?php

namespace Azuriom\Plugin\centralcorp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsWhitelist extends Model
{
    use HasFactory;

    protected $table = 'centralcorp_whitelist';
    protected $fillable = ['users'];
}

