<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChapterController extends Controller
{

    private $AppHelper;
    private $Chapters;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();  
        $this->Chapters = new Chapter();  
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
                $bookInfo = array();
                $bookInfo['bookId'] = $bookId;
                $bookInfo['status'] = 1;

                $resp = $this->Chapters->get_chapters_by_book_id($bookInfo);

                $chapterInfo = array();
                foreach ($resp as $key => $value) {
                    $chapterInfo[$key]['id'] = $value->id;
                    $chapterInfo[$key]['chapter'] = $value->chapter;
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $chapterInfo);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getChapterDetailsById(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $chapterId = (is_null($request->chapterId) || empty($request->chapterId)) ? "" : $request->chapterId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($chapterId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Chapter id is required.");
        } else {
            try {
                $resp = DB::table('chapters')->select('chapters.id as chapterId', 'chapters.pdf_path', 'chapters.chapter', 'books.book_name')
                                            ->join('books', 'books.id', '=', 'chapters.book_id')
                                            ->where('chapters.id', '=', $chapterId)
                                            ->first();
                
                $chapterInfo = array();
                $chapterInfo['id'] = $resp->chapterId;
                $chapterInfo['bookName'] = $resp->book_name;
                $chapterInfo['chapterName'] = $resp->chapter;
                $chapterInfo['pdfPath'] = $resp->pdf_path;

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $chapterInfo);
                
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
