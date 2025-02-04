<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';
    protected $fillable = ['name', 'location'];

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'branch_id', 'id');
    }
}
