<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $AppHelper;
    private $Book;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Book = new Book();
    }

    public function getBookList(Request $request) {
        try {
            $bookDetails = $this->Book->query_log();

            $bookList = array();
            foreach ($bookDetails as $key => $value) {
                $bookList['bookName'] = $value['book_name'];
                $bookList['bookCover'] = $value['book_cover'];
                $bookList['bookCategoryId'] = $value['book_category_id'];
                $bookList['year'] = $value['year'];
                $bookList['rating'] = $value['rating'];
            }

            return $this->AppHelper->responseEntityHandle(1, "Book List Successfully.", $bookDetails);
        } catch (\Exception $e) {
            $this->AppHelper->responseMessageHandle(0, $e->getMessage());
        }
    }   
}
