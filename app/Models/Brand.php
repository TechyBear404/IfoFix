<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'isDeleted'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class, 'deviceId');
    }
}
