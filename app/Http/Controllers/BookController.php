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
                $bookList['bookPrice'] = $value['book_price'];
                $bookList['year'] = $value['year'];
                $bookList['rating'] = $value['rating'];
            }

            return $this->AppHelper->responseEntityHandle(1, "Book List Successfully.", $bookDetails);
        } catch (\Exception $e) {
            $this->AppHelper->responseMessageHandle(0, $e->getMessage());
        }
    }

    public function getBookDetailsById(Request $request) {

        $bookId = (is_null($request->bookId) || empty($request->bookId)) ? "" : $request->bookId;
        $resp = $this->Book->query_find($bookId);

        if ($bookId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Book Id is Required");
        } if (empty($resp)) {
            return $this->AppHelper->responseMessageHandle(0, "Invalid Book Id.");
        } else {
            try {

                $bookDetails = array();
                $bookDetails['bookName'] = $resp['book_name'];
                $bookDetails['bookCover'] = $resp['book_cover'];
                $bookDetails['authorName'] = $resp['author_name'];
                $bookDetails['bookDescription'] = $resp['book_description'];
                $bookDetails['bookPrice'] = $resp['book_price'];
                $bookDetails['categoryId'] = $resp['book_category_id'];
                $bookDetails['rating'] = $resp['rating'];

                return $this->AppHelper->responseEntityHandle(1, "Operation Successfully.", $bookDetails);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
