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

    public function remove_cart_items_by_id($cartId) {
        $map['cart_id'] = $cartId;

        return $this->where($map)->delete();
    }

    public function remove_cart_item_by_id($itemInfo) {
        $map['cart_id'] = $itemInfo['cartId'];
        $map['book_id'] = $itemInfo['bookId'];

        return $this->where($map)->delete();
    }
}
