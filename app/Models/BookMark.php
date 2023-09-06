<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'clientId',
        'bookId',
        'pageNumber',
        'pageDescription',
        'time'
    ];

    public function add_log($bookmarkDetails) {
        $map['client_id'] = $bookmarkDetails['clientId'];
        $map['book_id'] = $bookmarkDetails['bookId'];
        $map['page_number'] = $bookmarkDetails['pageNumber'];
        $map['page_description'] = $bookmarkDetails['pageDescription'];
        $map['time'] = $bookmarkDetails['time'];
        // dd($map); die;
        return $this->create($map);
    }

    public function query_log($bookMarkDetails) {
        $map['client_id'] = $bookMarkDetails['clientId'];
        $map['book_id'] = $bookMarkDetails['bookId'];

        return $this->where($map)->get();
    }
}
