<?php

namespace Azuriom\Plugin\centralcorp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsIgnore extends Model
{
    use HasFactory;

    protected $table = 'centralcorp_ignored_folders';

    protected $fillable = ['folder_name'];
}

