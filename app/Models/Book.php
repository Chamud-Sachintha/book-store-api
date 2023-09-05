<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookName',
        'bookCover',
        'bookCategoryId',
        'bookDescription',
        'year',
        'authorName',
        'tags',
        'rating',
        'status',
        'create_time'
    ];

    public function query_log() {
        $map['status'] = 1;
        return $this->where($map)->get(['book_name', 'book_cover', 'book_category_id' ,'year', 'rating']);
    }

    public function query_find($bookId) {
        $map['id'] = $bookId;
        $map['status'] = 1;

        return $this->where($map)->first();
    }
}
