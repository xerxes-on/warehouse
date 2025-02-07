<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = ['name', 'location'];

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'warehouse_id', 'id');
    }

    public function warehouseProducts(): HasMany
    {
        return $this->hasMany(ProductWarehouse::class);
    }
}
