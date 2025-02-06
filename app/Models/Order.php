<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = ['total_price', 'status', 'user_id', 'shipment_id'];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    protected static function booted(): void
    {
        if (session('user_role') != 'admin') {
            static::addGlobalScope('only_user\'s', function (Builder $builder) {
                $builder->where('user_id', '=', auth()->user()->id);
            });
        }
        if (session('user_role') == 'admin') {
            static::addGlobalScope('cant_access_cart', function (Builder $builder) {
                $builder->where('status', '!=', OrderStatus::CART);
            });
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getSubtotal(): float
    {
        return $this->orderItems()->sum('total_price');
    }
}
