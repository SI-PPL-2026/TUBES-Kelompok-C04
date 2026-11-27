<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'unit',
        'minimum_stock',
        'current_stock',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'minimum_stock' => 'decimal:2',
            'current_stock' => 'decimal:2',
        ];
    }

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('current_stock', '<', 'minimum_stock');
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('current_stock', '<=', 0);
    }

    public function scopeSafeStock(Builder $query): Builder
    {
        return $query->where('current_stock', '>', 0)
            ->whereColumn('current_stock', '>=', 'minimum_stock');
    }

    public function isOutOfStock(): bool
    {
        return (float) $this->current_stock <= 0;
    }

    public function isBelowMinimum(): bool
    {
        return (float) $this->current_stock < (float) $this->minimum_stock;
    }

    public function stockDeficit(): float
    {
        return max(0.0, (float) $this->minimum_stock - (float) $this->current_stock);
    }

    public function stockStatus(): string
    {
        if ($this->isOutOfStock()) {
            return 'out';
        }

        if ($this->isBelowMinimum()) {
            return 'low';
        }

        return 'safe';
    }

    public function stockStatusLabel(): string
    {
        return match ($this->stockStatus()) {
            'out' => 'Habis',
            'low' => 'Di bawah minimum',
            default => 'Aman',
        };
    }
}