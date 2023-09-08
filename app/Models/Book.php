<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookName',
        'pdfPath',
        'bookCover',
        'bookCategoryId',
        'bookDescription',
        'bookPrice',
        'year',
        'authorName',
        'tags',
        'rating',
        'status',
        'create_time'
    ];

    public function query_log() {
        $map['status'] = 1;
        return $this->where($map)->get(['id as bookId', 'book_name', 'book_cover', 'author_name', 'book_price', 'book_category_id' ,'year', 'rating']);
    }

    public function query_find($bookId) {
        $map['id'] = $bookId;
        $map['status'] = 1;

        return $this->where($map)->first();
    }
}
