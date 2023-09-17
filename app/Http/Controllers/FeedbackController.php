<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Book;
use App\Models\ClientReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    private $Feedback;
    private $AppHelper;
    private $Book;
    private $User;

    public function __construct()
    {
        $this->Feedback = new ClientReview();
        $this->AppHelper = new AppHelper();
        $this->User = new User();
        $this->Book = new Book();
    }

    public function addNewClientReview(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;
        $bookId = (is_null($request->bookId) || empty($request->bookId)) ? "" : $request->bookId;
        $rating = (is_null($request->rating) || empty($request->rating)) ? "" : $request->rating;
        $feedback = (is_null($request->feedback) || empty($request->feedback)) ? "" : $request->feedback;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is Required.");
        } else if ($clientId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client Id is Required.");
        } else if ($bookId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Book Id is Required.");
        } else if ($rating == "") {
            return $this->AppHelper->responseMessageHandle(0, "Rating is Required.");
        } else if ($feedback == "") {
            return $this->AppHelper->responseMessageHandle(0, "Feedback is Required.");
        } else {
            try {
                $veryfy_client = $this->User->find_by_id($clientId);
                $verify_book = $this->Book->query_find($bookId);

                if (!$veryfy_client) {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Client Id.");
                } else if (!$verify_book) {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Book id");
                } else {
                    $feedbackDetails = array();

                    $feedbackDetails['clientId'] = $clientId;
                    $feedbackDetails['bookId'] = $bookId;
                    $feedbackDetails['rating'] = $rating;
                    $feedbackDetails['feedback'] = $feedback;
                    $feedbackDetails['status'] = 0;
                    $feedbackDetails['time'] = $this->AppHelper->get_date_and_time();

                    $resp = $this->Feedback->add_log($feedbackDetails);

                    $newFeedback = array();
                    if ($resp) {
                        $newFeedback['id'] = $resp['id'];
                        $newFeedback['clientId'] = $resp['client_id'];
                        $newFeedback['bookId'] = $resp['book_id'];
                        $newFeedback['rating'] = $resp['rating'];
                        $newFeedback['feedback'] = $resp['feedback'];
                        $newFeedback['time'] = $resp['time'];

                        return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $newFeedback);
                    }
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getAllUserFeedbacks(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $bookId = (is_null($request->bookId) || empty($request->bookId)) ? "" : $request->bookId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($bookId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Book id is required.");
        } else {
            try {
                $info = array();
                $info['bookId'] = $bookId;
                $info['status'] = 1;

                $resp = DB::table('client_reviews')->select('client_reviews.rating', 'client_reviews.feedback', 'client_reviews.time', 'users.first_name', 'users.last_name')
                                                    ->join('users', 'users.id', '=', 'client_reviews.client_id')
                                                    ->where('client_reviews.status', '=', 1)
                                                    ->where('client_reviews.book_id', '=', $bookId)
                                                    ->get();

                $feedBackList = array();
                foreach ($resp as $key => $value) {
                    $feedBackList[$key]['firstName'] = $value->first_name;
                    $feedBackList[$key]['lastNamde'] = $value->last_name;
                    $feedBackList[$key]['rating'] = $value->rating;
                    $feedBackList[$key]['feedback'] = $value->feedback;
                    $feedBackList[$key]['time'] = date('Y-m-d H:i:s', $value->time);
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $feedBackList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
