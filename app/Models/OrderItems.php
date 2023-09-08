<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'book_id',
        'quantity',
        'time'
    ];

    public function place_new_order_items($orderItemsInfo) {
        
        $map = array();
        foreach ($orderItemsInfo as $key => $value) {
            $map[$key]['order_id'] = $value['orderId'];
            $map[$key]['book_id'] = $value['bookId'];
            $map[$key]['quantity'] = $value['quantity'];
            $map[$key]['time'] = $value['time'];
        }

        return $this->insert($map);
    }
}
