<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    private $User;
    private $AppHelper;

    public function __construct()
    {   
        $this->User = new User();
        $this->AppHelper = new AppHelper();
    }

    public function registerNewUser(Request $request) {

        $firstName = (is_null($request->firstName) || empty($request->firstName)) ? "" : $request->firstName;
        $lastName = (is_null($request->lastName) || empty($request->lastName)) ? "" : $request->lastName;
        $email = (is_null($request->email) || empty($request->email)) ? "" : $request->email;
        $password = (is_null($request->password) || empty($request->password)) ? "" : $request->password;

        if ($firstName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Please Enter First Name");
        } else if ($lastName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Please Enter Last Name");
        } else if ($email == "") {
            return $this->AppHelper->responseMessageHandle(0, "Please Enter Email Address");
        } else if ($password == "") {
            return $this->AppHelper->responseMessageHandle(0, "Please Enter Password");
        } else {

            $checkEmail = $this->User->check_email($email);

            if ($checkEmail) {
                return $this->AppHelper->responseMessageHandle(3, "Email Already Exisist");
            }

            $data['firstName'] = $firstName;
            $data['lastName'] = $lastName;
            $data['email'] = $email;
            $data['password'] = $password;

            try {
                $user = $this->User->add_user($data);

                if ($user) {
                    return $this->AppHelper->responseMessageHandle(1, "User Created Successfully.");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, 'Error Occured ' . $e->getMessage());
            }
        }
    }

    public function loginUser(Request $request) {

        $userName = (is_null($request->userName) || empty($request->userName)) ? "" : $request->userName;
        $password = (is_null($request->password) || empty($request->password)) ? "" : $request->password;

        if ($userName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Username is Required");
        } else if ($password == "") {
            return $this->AppHelper->responseMessageHandle(0, "Password is required.");
        } else {

            $userInfo = array();
            $userInfo['userEmail'] = $userName;

            $userStatus = $this->User->check_user_status($userInfo);

            if ($userStatus) {
                return $this->AppHelper->responseMessageHandle(0, "Your Account is Deleted.");
            }

            $user = $this->User->check_email($userName);

            if (!empty($user)) {
                if (Hash::check($password, $user['password'])) {
                    $data['id'] = $user['id'];
                    $data['firstName'] = $user['first_name'];
                    $data['lastName'] = $user['last_name'];
                    $data['email'] = $user['email'];
                    
                    $token = $this->AppHelper->generateAuthToken($user);

                    $authdetails['uid'] = $user['id'];
                    $authdetails['token'] = $token;
                    $authdetails['time'] = $this->AppHelper->day_time();

                    $this->User->update_login_time($authdetails);

                    return $this->AppHelper->responseEntityHandle(1, "Login Successfuly", $data, $token);
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Incorrect Username or Password");
                }   
            } else {
                return $this->AppHelper->responseMessageHandle(0, "Incorrect Username or Password");
            }
        }
    }

    public function googleAuthSignin(request $request) {

        $emailAddress = (is_null($request->emailAddress) || empty($request->emailAddress)) ? "" : $request->emailAddress;
        $firstName = (is_null($request->firstName) || empty($request->firstName)) ? "" : $request->firstName;
        $lastName = (is_null($request->lastName) || empty($request->lastName)) ? "" : $request->lastName;

        if ($emailAddress == "") {
            return $this->AppHelper->responseMessageHandle(0, "Email Address is required.");
        } else if ($firstName == "") {
            return $this->AppHelper->responseMessageHandle(0, "First name is required.");
        } else {
            try {
                $user = $this->User->check_email($emailAddress);

                if (!empty($user)) {
                    $token = $this->AppHelper->generateAuthToken($user);

                    $data['id'] = $user['id'];
                    $data['firstName'] = $user['first_name'];
                    $data['lastName'] = $user['last_name'];
                    $data['email'] = $user['email'];

                    $authdetails['uid'] = $user['id'];
                    $authdetails['token'] = $token;
                    $authdetails['time'] = $this->AppHelper->day_time();

                    $this->User->update_login_time($authdetails);

                    return $this->AppHelper->responseEntityHandle(1, "Login Successfuly", $data, $token);
                } else {
                    $token = $this->AppHelper->generateAuthToken($user);
                    $data['firstName'] = $firstName;
                    $data['lastName'] = $lastName;
                    $data['email'] = $emailAddress;
                    $data['password'] = Str::random(10);

                    try {
                        $user = $this->User->add_user($data);

                        $authdetails['uid'] = $user['id'];
                        $authdetails['token'] = $token;
                        $authdetails['time'] = $this->AppHelper->day_time();
                        
                        $this->User->update_login_time($authdetails);
        
                        if ($user) {
                            return $this->AppHelper->responseEntityHandle(1, "User Created Successfully.", $user);
                        }
                    } catch (\Exception $e) {
                        return $this->AppHelper->responseMessageHandle(0, 'Error Occured ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function facebookAuthSignin(request $request) {

        $emailAddress = (is_null($request->emailAddress) || empty($request->emailAddress)) ? "" : $request->emailAddress;
        $firstName = (is_null($request->firstName) || empty($request->firstName)) ? "" : $request->firstName;
        $lastName = (is_null($request->lastName) || empty($request->lastName)) ? "" : $request->lastName;

        if ($emailAddress == "") {
            return $this->AppHelper->responseMessageHandle(0, "Email Address is required.");
        } else if ($firstName == "") {
            return $this->AppHelper->responseMessageHandle(0, "First name is required.");
        } else {
            try {
                $user = $this->User->check_email($emailAddress);

                if (!empty($user)) {
                    $token = $this->AppHelper->generateAuthToken($user);

                    $data['id'] = $user['id'];
                    $data['firstName'] = $user['first_name'];
                    $data['lastName'] = $user['last_name'];
                    $data['email'] = $user['email'];

                    $authdetails['uid'] = $user['id'];
                    $authdetails['token'] = $token;
                    $authdetails['time'] = $this->AppHelper->day_time();

                    $this->User->update_login_time($authdetails);

                    return $this->AppHelper->responseEntityHandle(1, "Login Successfuly", $data, $token);
                } else {
                    $token = $this->AppHelper->generateAuthToken($user);
                    $data['firstName'] = $firstName;
                    $data['lastName'] = $lastName;
                    $data['email'] = $emailAddress;
                    $data['password'] = Str::random(10);

                    try {
                        $user = $this->User->add_user($data);

                        $authdetails['uid'] = $user['id'];
                        $authdetails['token'] = $token;
                        $authdetails['time'] = $this->AppHelper->day_time();
                        
                        $this->User->update_login_time($authdetails);
        
                        if ($user) {
                            return $this->AppHelper->responseEntityHandle(1, "User Created Successfully.", $user);
                        }
                    } catch (\Exception $e) {
                        return $this->AppHelper->responseMessageHandle(0, 'Error Occured ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
