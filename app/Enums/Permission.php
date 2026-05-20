<?php

namespace App\Enums;

enum Permission: string
{
    // Products
    case VIEW_PRODUCTS   = 'view products';
    case CREATE_PRODUCTS = 'create products';
    case EDIT_PRODUCTS   = 'edit products';
    case DELETE_PRODUCTS = 'delete products';

    // Orders
    case VIEW_OWN_ORDERS = 'view own orders';
    case VIEW_ALL_ORDERS = 'view all orders';
    case MANAGE_ORDERS   = 'manage orders';

    // Users
    case VIEW_USERS   = 'view users';
    case MANAGE_USERS = 'manage users';

    // Categories
    case MANAGE_CATEGORIES = 'manage categories';

    // Reviews
    case CREATE_REVIEWS = 'create reviews';
    case MANAGE_REVIEWS = 'manage reviews';

    // Coupons
    case MANAGE_COUPONS = 'manage coupons';

    // Returns array of all permission values
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    // Permissions for each role
    public static function adminPermissions(): array
    {
        return self::values(); // Admin gets everything
    }

    public static function customerPermissions(): array
    {
        return [
            self::VIEW_PRODUCTS->value,
            self::VIEW_OWN_ORDERS->value,
            self::CREATE_REVIEWS->value,
        ];
    }
}
