<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;

class ChapterController extends Controller
{

    private $AppHelper;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();    
    }

    public function getBookChaptersList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $bookId = (is_null($request->bookId) || empty($request->bookId)) ? "" : $request->bookId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($bookId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Book id is required.");
        } else {
            try {
                
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
