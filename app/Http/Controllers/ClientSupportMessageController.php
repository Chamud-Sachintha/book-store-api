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
        $title = (is_null($request->title) || empty($request->title)) ? "" : $request->title;
        $emailAddress = (is_null($request->emailAddress) || empty($request->emailAddress)) ? "" :$request->emailAddress;
        $message = (is_null($request->message) || empty($request->message)) ? "" : $request->message;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is Requyired.");
        } else if ($title == "") {
            return $this->AppHelper->responseMessageHandle(0, "First Name is Requiered.");
        } else if ($emailAddress == "") {   
            return $this->AppHelper->responseMessageHandle(0, "Email Address is required.");
        } else if ($message == "") {    
            return $this->AppHelper->responseMessageHandle(0, "Message is requirted.");
        } else {
            try {
                $supportmessage = array();
                $supportmessage['title'] = $title;
                $supportmessage['email'] = $emailAddress;
                $supportmessage['message'] = $message;
                $supportmessage['time'] = $this->AppHelper->get_date_and_time();

                $resp = $this->ClientSupportMessage->add_log($supportmessage);

                if ($resp) {
                    return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
