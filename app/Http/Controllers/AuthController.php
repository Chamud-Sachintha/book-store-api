<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $mobileNumber = (is_null($request->mobileNumber) || empty($request->mobileNumber)) ? "" : $request->mobileNumber;
        $email = (is_null($request->email) || empty($request->email)) ? "" : $request->email;
        $password = (is_null($request->password) || empty($request->password)) ? "" : $request->password;

        if ($firstName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Please Enter First Name");
        } else if ($lastName == "") {
            return $this->AppHelper->responseMessageHandle(0, "Please Enter Last Name");
        } else if ($mobileNumber == "") {
            return $this->AppHelper->responseMessageHandle(0, "Please Enter Mobile Number");
        } else if ($email == "") {
            return $this->AppHelper->responseMessageHandle(0, "Please Enter Email Address");
        } else if ($password == "") {
            return $this->AppHelper->responseMessageHandle(0, "Please Enter Password");
        } else {

            $checkEmail = $this->User->check_email($email);

            if ($checkEmail) {
                return $this->AppHelper->responseMessageHandle(0, "Email Already Exisist");
            }

            $data['firstName'] = $firstName;
            $data['lastName'] = $lastName;
            $data['mobileNumber'] = $mobileNumber;
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

            $user = $this->User->check_email($userName);

            if (!empty($user)) {
                if (Hash::check($password, $user['password'])) {
                    $data['id'] = $user['id'];
                    $data['firstName'] = $user['first_name'];
                    $data['lastName'] = $user['last_name'];
                    $data['mobileNumber'] = $user['mobile_number'];
                    $data['email'] = $user['email'];
                    
                    $token = $this->AppHelper->generateAuthToken($user);

                    $authdetails['uid'] = $user['id'];
                    $authdetails['token'] = $token;
                    $authdetails['time'] = $this->AppHelper->day_time();

                    $this->User->update_login_time($authdetails);

                    return $this->AppHelper->responseEntityHandle(1, "Login Successfuly", $data, $token);
                } else {
                    return $this->AppHelper->responseMessageHandle(0, "Username or Password is Incorrect.");
                }   
            } else {
                return $this->AppHelper->responseMessageHandle(0, "Username or Password is Incorrect.");
            }
        }
    }
}
