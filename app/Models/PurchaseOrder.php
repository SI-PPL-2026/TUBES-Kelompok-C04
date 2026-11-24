<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    public const STATUS_DIPESAN = 'dipesan';
    public const STATUS_DITERIMA = 'diterima';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    protected $fillable = [
        'order_number',
        'supplier_id',
        'user_id',
        'order_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DITERIMA => 'Diterima',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
            default => 'Dipesan',
        };
    }
}