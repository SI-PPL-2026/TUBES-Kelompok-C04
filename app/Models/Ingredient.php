<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'unit',
        'minimum_stock',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'minimum_stock' => 'decimal:2',
        ];
    }
}
