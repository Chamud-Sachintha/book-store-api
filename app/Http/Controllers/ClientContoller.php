<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function updateProfileInformations() {

    }
}
