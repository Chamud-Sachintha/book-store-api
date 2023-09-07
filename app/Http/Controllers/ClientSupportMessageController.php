<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\ClientSupportMessage;
use Illuminate\Http\Request;

class ClientSupportMessageController extends Controller
{
    private $ClientSupportMessage;
    private $AppHelper;

    public function __construct()
    {
        $this->ClientSupportMessage = new ClientSupportMessage();
        $this->AppHelper = new AppHelper();
    }

    public function createSupportMessage(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $first_name = (is_null($request->firstName) || empty($request->firstName)) ? "" : $request->firstName;
        $last_name = (is_null($request->lastName) || empty($request->lastName)) ? "" : $request->lastName;
        $emailAddress = (is_null($request->emailAddress) || empty($request->emailAddress)) ? "" :$request->emailAddress;
        $message = (is_null($request->message) || empty($request->message)) ? "" : $request->message;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is Requyired.");
        } else if ($first_name == "") {
            return $this->AppHelper->responseMessageHandle(0, "First Name is Requiered.");
        } else if ($last_name == "") {
            return $this->AppHelper->responseMessageHandle(0, "Last name is required.");
        } else if ($emailAddress == "") {   
            return $this->AppHelper->responseMessageHandle(0, "Email Address is required.");
        } else if ($message == "") {    
            return $this->AppHelper->responseMessageHandle(0, "Message is requirted.");
        } else {
            try {
                
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
