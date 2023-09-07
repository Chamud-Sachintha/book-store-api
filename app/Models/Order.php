<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'time'
    ];

    public function place_order($orderInfo) {
        $map['client_id'] = $orderInfo['clientId'];

        return $this->create($map);
    }

    public function verify_order($clientId) {
        $map['client_id'] = $clientId;

        return $this->where($map)->first();
    }
}
