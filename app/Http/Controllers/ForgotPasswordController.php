<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Mail\AuthCodeMail;
use App\Models\ForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{

    private $AppHelper;
    private $ForgotPass;
    private $User;

    public function __construct()
    {
        $this->ForgotPass = new ForgotPassword();
        $this->AppHelper = new AppHelper();
        $this->User = new User();
    }

    public function sendOTPCodeForUser(Request $request) {

        $emailAddress = (is_null($request->emailAddress) || empty($request->emailAddress)) ? "" : $request->emailAddress;

        if ($emailAddress == "") {
            return $this->AppHelper->responseMessageHandle(0, "Email is required.");
        } else {
            try {
                $user = $this->User->check_email($emailAddress);

                if (!empty($user)) {
                    $authCode = Str::random(5);

                    $details = [
                        'title' => 'Mail from dpuremaths.lk',
                        'body' => $authCode
                    ];

                    Mail::to($emailAddress)->send(new AuthCodeMail($details));

                    $codeInfo = array();
                    $codeInfo['authCode'] = $authCode;
                    $codeInfo['emailAddress'] = $emailAddress;
                    $codeInfo['time'] = $this->AppHelper->get_date_and_time();

                    $check_log = $this->ForgotPass->query_find($emailAddress);

                    if ($check_log) {
                        $update = $this->ForgotPass->update_log($codeInfo);
                    }

                    $insert = $this->ForgotPass->add_log($codeInfo);

                    return $this->AppHelper->responseMessageHandle(1, "Email Sent Successfully.");
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "There is No Account for that Email.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function validateOTPCode(Request $request) {

        $emailAddress = (is_null($request->emailAddress) || empty($request->emailAddress)) ? "" : $request->emailAddress;
        $authCode = (is_null($request->authCode) || empty($request->authCode)) ? "" : $request->authCode;

        if ($emailAddress == "") {
            return $this->AppHelper->responseMessageHandle(0, "Email is required.");
        } else {
            try {
                $resp = $this->ForgotPass->query_find($emailAddress);

                if ($resp) {
                    
                    if ($authCode == $resp->auth_code) {
                        return $this->AppHelper->responseMessageHandle(1, "Code Matched.");
                    } else {
                        return $this->AppHelper->responseMessageHandle(0, "Code Not Matched.");
                    }
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }

    }

    public function changePasswordForUser(Request $request) {

        $authCode = (is_null($request->authCode) || empty($request->authCode)) ? "" : $request->authCode;
        $newPassword = (is_null($request->newPassword) || empty($request->newPassword)) ? "" : $request->newPassword;

        if ($authCode == "") {
            return $this->AppHelper->responseMessageHandle(0, "Auth Code is required.");
        } else if ($newPassword == "") {
            return $this->AppHelper->responseMessageHandle(0, "Password is required.");
        } else {
            try {
                $check_code = $this->ForgotPass->get_log_by_code($authCode);

                if (!empty($check_code)) {
                    $passInfo = array();

                    $passInfo['emailAddress'] = $check_code->email;
                    $passInfo['password'] = Hash::make($newPassword);

                    $resp = $this->User->update_password($passInfo);

                    return $this->AppHelper->responseMessageHandle(1, "Password Changed.");
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Auth Code");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
