<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientContoller extends Controller
{

    private $User;
    private $AppHelper;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->User = new User();    
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
                    $userDetails['mobileNumber'] = $resp['mobile_number'];

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
        $mobileNumber = (is_null($request->mobileNumber) || empty($request->mobileNumber)) ? "" : $request->mobileNumber;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "token is required.");
        } else if ($emailAddress == "") {
            return $this->AppHelper->responseMessageHandle(0, "Email is required.");
        } else if ($firstName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Firstname is required.");
        } else if ($lastName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Lastname is Required.");
        } else if ($mobileNumber == "") {
            return $this->AppHelper->responseMessageHandle(0, "Mobile Numbner is required.");
        } else {
            try {
                $verify_email = $this->User->check_email($emailAddress);

                if ($verify_email) {
                    $newUserDetails = array();

                    $newUserDetails['first_name'] = $firstName;
                    $newUserDetails['last_name'] = $lastName;
                    $newUserDetails['email'] = $emailAddress;
                    $newUserDetails['mobile_number'] = $mobileNumber;

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
                                            ->get();

                $paidBookList = array();
                foreach ($resp as $key => $value) {
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
}
