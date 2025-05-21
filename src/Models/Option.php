<?php

namespace Azuriom\Plugin\Centralcorp\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'centralcorp_options';

    protected $fillable = ['name', 'value'];
    
    public function getValueAs(string $type)
    {
        return match ($type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            default => $this->value,
        };
    }
}
