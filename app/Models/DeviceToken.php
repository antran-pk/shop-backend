<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    const TYPES = [
        'ios' => 1,
        'android' => 2,
    ];

    protected $fillable = ['token', 'user_id', 'type'];
}
