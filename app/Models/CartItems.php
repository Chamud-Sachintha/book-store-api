<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'book_id',
        'quantity',
        'time',
    ];

    public function create_cart_items($cartItemsInfo) {
        $map['cart_id'] = $cartItemsInfo['cartId'];
        $map['book_id'] = $cartItemsInfo['bookId'];
        $map['quantity'] = $cartItemsInfo['quantity'];
        $map['time'] = $cartItemsInfo['time'];

        return $this->create($map);
    }
}
