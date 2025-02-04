<?php

namespace App\Enums;

enum OrderStatus: int
{
    case CART = 0;
    case ORDERED = 1;
    case DELIVERED = 2;

    public function toString(): string
    {
        return match ($this) {
            self::CART => 'In Cart',
            self::ORDERED => 'Ordered',
            self::DELIVERED => 'Delivered',
        };
    }
}
