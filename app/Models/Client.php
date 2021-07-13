<?php

namespace App\Models;

use Facade\Ignition\DumpRecorder\Dump;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
class Client extends Model
{
    protected $guarded = [];

    protected $casts = [
        'phone' => 'array'
    ];
    public function orders ()
    {
        return $this->hasMany(Order::class);
    }
}
