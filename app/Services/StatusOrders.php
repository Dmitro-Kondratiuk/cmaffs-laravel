<?php

namespace App\Services;

class StatusOrders
{
    const PENDING   = 'pending';
    const ACCEPTED  = 'accepted';
    const REJECTED  = 'rejected';
    const DELIVERED = 'delivered';
    const REFUSED = 'refused';

    public static function statuses(){
        return [
            self::PENDING   => self::PENDING,
            self::ACCEPTED  => self::ACCEPTED,
            self::REJECTED  => self::REJECTED,
            self::DELIVERED => self::DELIVERED,
            self::REFUSED  => self::REFUSED,
        ];
    }
}
