<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING    = 'pending';
    case CONFIRMED  = 'confirmed';
    case PROCESSING = 'processing';
    case SHIPPED    = 'shipped';
    case DELIVERED  = 'delivered';
    case CANCELLED  = 'cancelled';
    case REFUNDED   = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::PENDING    => 'في الانتظار',
            self::CONFIRMED  => 'مؤكد',
            self::PROCESSING => 'جاري التجهيز',
            self::SHIPPED    => 'تم الشحن',
            self::DELIVERED  => 'تم التسليم',
            self::CANCELLED  => 'ملغي',
            self::REFUNDED   => 'مسترجع',
        };
    }
}
