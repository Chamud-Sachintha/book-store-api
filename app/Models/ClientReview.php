<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'book_id',
        'rating',
        'feedback',
        'time'
    ];

    public function add_log($feedbackDetails) {
        $map['client_id'] = $feedbackDetails['clientId'];
        $map['book_id'] = $feedbackDetails['bookId'];
        $map['rating'] = $feedbackDetails['rating'];
        $map['feedback'] = $feedbackDetails['feedback'];
        $map['time'] = $feedbackDetails['time'];

        return $this->create($map);
    }
}
