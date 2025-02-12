<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $table = 'products';

    protected $fillable = ['name', 'price', 'deleted_at'];

    public function shipments(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id');
    }

    public function searchableAs(): string
    {
        return 'products_index';
    }
}
