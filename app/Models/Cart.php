<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'time'
    ];

    public function create_cart($cartInfo) {
        $map['client_id'] = $cartInfo['clientId'];
        $map['time'] = $cartInfo['time'];

        return $this->create($map);
    }

    public function verify_cart($clientId) {
        $map['client_id'] = $clientId;

        return $this->where($map)->first();
    }

    public function remove_cart_for_user($clientId) {
        $map['client_id'] = $clientId;

        return $this->where($map)->delete();
    }

    public function remove_cart_by_cartId($cartId) {
        $map['id'] = $cartId;

        return $this->where($map)->delete();
    }
}
