<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientContoller extends Controller
{

    private $User;
    private $AppHelper;
    private $OrderItems;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->User = new User();    
        $this->OrderItems = new OrderItems();
    }

    public function getProfileInformations(Request $request) {
        
        $emailAddress = (is_null($request->email) || empty($request->email)) ? "" : $request->email;
        $token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;

        if ($emailAddress == "") {
            return $this->AppHelper->responseMessageHandle(0, "Email Addres is required.");
        } if ($token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is Required");
        } else {
            try {
                $verifyEmail = $this->User->check_email($emailAddress);

                if ($verifyEmail) {
                    $resp = $this->User->query_log($emailAddress);

                    $userDetails = array();
                    $userDetails['firstName'] = $resp['first_name'];
                    $userDetails['lastName'] = $resp['last_name'];
                    $userDetails['emailAddress']  =$resp['email'];

                    return $this->AppHelper->responseEntityHandle(1, "Operation Successfuly", $userDetails);
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "There is No Informations.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function updateProfileInformations(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $emailAddress = (is_null($request->email) || empty($request->email)) ? "" : $request->email;
        $firstName = (is_null($request->firstName) || empty($request->firstName)) ? "" : $request->firstName;
        $lastName = (is_null($request->lastName) || empty($request->lastName)) ? "" : $request->lastName;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "token is required.");
        } else if ($emailAddress == "") {
            return $this->AppHelper->responseMessageHandle(0, "Email is required.");
        } else if ($firstName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Firstname is required.");
        } else if ($lastName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Lastname is Required.");
        } else {
            try {
                $verify_email = $this->User->check_email($emailAddress);

                if ($verify_email) {
                    $newUserDetails = array();

                    $newUserDetails['first_name'] = $firstName;
                    $newUserDetails['last_name'] = $lastName;
                    $newUserDetails['email'] = $emailAddress;

                    $resp = $this->User->update_user($newUserDetails);

                    if ($resp) {
                        return $this->AppHelper->responseMessageHandle(1, "Operation Successfully.");
                    } else {
                        return $this->AppHelper->responseMessageHandle(0, "There is Error Occured.");
                    }
                } else {    
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Email.");
                }   
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getClientPaidBooksList(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $clientId = (is_null($request->cid) || empty($request->cid)) ? "" : $request->cid;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($clientId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client Id is Required.");
        } else {    
            try {
                $resp = DB::table('books')->select('books.id as bookId', 'books.book_name', 'books.book_cover', 'books.author_name')
                                            ->join('order_items', 'order_items.book_id', '=', 'books.id')
                                            ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                            ->where('orders.payment_status', '=', 1)
                                            ->where('orders.client_id', '=', $clientId)
                                            ->get();

                $paidBookList = array();
                foreach ($resp as $key => $value) {
                    $paidBookList[$key]['bookId'] = $value->bookId;
                    $paidBookList[$key]['bookName'] = $value->book_name;
                    $paidBookList[$key]['authorName'] = $value->author_name;
                    $paidBookList[$key]['bookCover'] = $value->book_cover;
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $paidBookList);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function verifyPaidBookByIdandUid(request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $bookId = (is_null($request->bookId) || empty($request->bookId)) ? "" : $request->bookId;
        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($bookId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Book id is required");
        } else if ($clientId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client id is required.");
        } else {
            try {
                $bookInfo = array();
                $bookInfo['bookId'] = $bookId;
                $bookInfo['clientId'] = $clientId;

                $resp = DB::table('order_items')->select('order_items.book_id as bookId')
                                                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                                ->where('orders.client_id', '=', $clientId)
                                                ->where('order_items.book_id', '=', $bookId)
                                                ->get();

                $result = array();
                if (count($resp) > 0) {
                    $result['buyStatus'] = true;
                } else {
                    $result['buyStatus'] = false;
                }

                return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $result);
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
