<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Book;
use App\Models\ClientReview;
use App\Models\User;
use Illuminate\Http\Request;

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
}
