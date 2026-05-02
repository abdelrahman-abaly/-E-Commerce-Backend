<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID             = 'unpaid';
    case PAID               = 'paid';
    case PARTIALLY_REFUNDED = 'partially_refunded';
    case REFUNDED           = 'refunded';
}
