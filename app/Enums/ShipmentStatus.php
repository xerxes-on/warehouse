<?php

namespace App\Enums;

enum ShipmentStatus: int
{
    case IN_WAREHOUSE = 0;
    case DELIVERING = 1;
    case DELIVERED = 2;
    case RETURNED = 3;

    public function toString(): string
    {
        return match ($this) {
            self::IN_WAREHOUSE => 'In Warehouse',
            self::DELIVERING => 'Being delivered',
            self::DELIVERED => 'Delivered',
            self::RETURNED => 'Returned',
        };
    }
}
