<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'name',
        'isDeleted'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'deviceId');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'deviceId');
    }
}
