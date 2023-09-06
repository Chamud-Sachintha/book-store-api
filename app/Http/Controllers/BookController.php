<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Book;
use App\Models\BookMark;
use App\Models\User;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $AppHelper;
    private $Book;
    private $BookMark;
    private $User;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Book = new Book();
        $this->BookMark = new BookMark();
        $this->User = new User();
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

    public function createBookMark(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $pageNumber = (is_null($request->pageNumber) || empty($request->pageNumber)) ? "" : $request->pageNumber;
        $pageDescription = (is_null($request->pageDescription) || empty($request->pageDescription)) ? "" : $request->pageDescription;
        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;
        $bookId = (is_null($request->bookId) || empty($request->bookId)) ? "" : $request->bookId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is Required.");
        } else if ($pageNumber == "") {
            return $this->AppHelper->responseMessageHandle(0, "Page number is required.");
        } else if ($pageDescription == "") {
            return $this->AppHelper->responseMessageHandle(0, "Page Description is required.");
        } else if ($clientId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client id is required.");
        } else if ($bookId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Book id is Required.");
        } else {

            try {
                $veryfy_client = $this->User->find_by_id($clientId);
                $verify_book = $this->Book->query_find($bookId);

                if ($veryfy_client) {
                    if ($verify_book) {
                        $bookMarkDetails = array();

                        $bookMarkDetails['clientId'] = $clientId;
                        $bookMarkDetails['bookId'] = $bookId;
                        $bookMarkDetails['pageNumber'] = $pageNumber;
                        $bookMarkDetails['pageDescription'] = $pageDescription;
                        $bookMarkDetails['time'] = $this->AppHelper->get_date_and_time();

                        $resp = $this->BookMark->add_log($bookMarkDetails);

                        if ($resp) {
                            return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $resp);
                        } else {
                            return $this->AppHelper->responseMessageHandle(0, "There is Error Occur.");
                        }
                    } else {
                        return $this->AppHelper->responseMessageHandle(0, "Invalid Book Id.");
                    }
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Client Id.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getBookMarkListByUser(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $client_id = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;
        $book_id = (is_null($request->bookId) || empty($request->bookId)) ? "" : $request->bookId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is Required");
        } else if ($client_id == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client id is reuired.");
        } else if ($book_id == "") {
            return $this->AppHelper->responseMessageHandle(0, "Bookm id is Required.");
        } else {
            try {   
                $veryfy_client = $this->User->find_by_id($client_id);
                $verify_book = $this->Book->query_find($book_id);

                if (!$veryfy_client) {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Client Id.");
                } else if (!$verify_book) {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Book Id");
                } else {
                    $bookMarkDetails = array();

                    $bookMarkDetails['clientId'] = $client_id;
                    $bookMarkDetails['bookId'] = $book_id;

                    $resp = $this->BookMark->query_log($bookMarkDetails);

                    if ($resp) {

                        $bookMarkList = array();                        
                        foreach($resp as $key => $value) {
                            $bookMarkList['pageNumber'] = $value['page_number'];
                            $bookMarkList['pageDescription'] = $value['page_description'];
                        }

                        return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $bookMarkList);
                    } else {
                        return $this->AppHelper->responseMessageHandle(0 ,"There is some error occur");
                    }
                }

            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
