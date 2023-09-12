<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'chapter',
        'page',
        'status',
        'time'
    ];

    public function get_chapters_by_book_id($bookInfo) {
        $map['book_id'] = $bookInfo['bookId'];
        $map['status'] = 1;
    }
}
