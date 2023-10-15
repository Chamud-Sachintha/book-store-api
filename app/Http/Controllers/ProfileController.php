<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $AppHelper;
    private $Profile;

    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Profile = new Profile();
    }

    public function checkProfileIsFilled(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $userId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($userId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client Id is required.");
        } else {
            try {
                $resp = $this->Profile->query_find($userId);

                if (!empty($resp)) {
                    $profileData = array();
                    $profileData['isProfileOk'] = true;

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $profileData);
                } else {
                    $profileData = array();
                    $profileData['isProfileOk'] = false;
                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $profileData);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function addProfileDetails(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $userId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;
        $age = (is_null($request->age) || empty($request->age)) ? "" : $request->age;
        $gender = (is_null($request->gender) || empty($request->gender)) ? "" : $request->gender;
        $nicNumber = (is_null($request->nicNumber) || empty($request->nicNumber)) ? "" : $request->nicNumber;
        $mobileNumber = (is_null($request->mobileNumber) || empty($request->mobileNumber)) ? "" : $request->mobileNumber;
        $schoolName = (is_null($request->schoolName) || empty($request->schoolName)) ? "" : $request->schoolName;
        $grade = (is_null($request->grade) || empty($request->grade)) ? "" : $request->grade;
        $district = (is_null($request->district) || empty($request->district)) ? "" : $request->district;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is Required");
        } else if ($userId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client id is Required");
        } else if ($age == "") {
            return $this->AppHelper->responseMessageHandle(0, "Age is Required");
        } else if ($gender == "") {
            return $this->AppHelper->responseMessageHandle(0, "Gender is Required");
        } else if ($nicNumber == "") {
            return $this->AppHelper->responseMessageHandle(0, "NIC Number is Required");
        } else if ($mobileNumber == "") {
            return $this->AppHelper->responseMessageHandle(0, "Mobile Number is Required");
        } else if ($schoolName == "") {
            return $this->AppHelper->responseMessageHandle(0, "School Name is Required");
        } else if ($grade == "") {
            return $this->AppHelper->responseMessageHandle(0, "Grade is Required");
        } else if($district == "") {
            return $this->AppHelper->responseMessageHandle(0, "District is Required.");
        } else {
            try {
                $userProfile = $this->Profile->query_find($userId);

                $profileInfo = array();
                $profileInfo['userId'] = $userId;
                $profileInfo['age'] = $age;
                $profileInfo['sex'] = $gender;
                $profileInfo['nicNumber'] = $nicNumber;
                $profileInfo['mobileNumber'] = $mobileNumber;
                $profileInfo['schoolName'] = $schoolName;
                $profileInfo['grade'] = $grade;
                $profileInfo['district'] = $district;
                $profileInfo['time'] = $this->AppHelper->get_date_and_time();

                $resp = null;
                if (empty($userProfile)) {
                    $resp = $this->Profile->add_log($profileInfo);
                } else {
                    $profile = array();
                    $resp = $this->Profile->update_log($profileInfo);

                    $profile['user_id'] = $profileInfo['userId'];
                    $profile['age'] = $profileInfo['age'];
                    $profile['sex'] = $profileInfo['sex'];
                    $profile['nic_number'] = $profileInfo['nicNumber'];
                    $profile['mobileNumber'] = $profileInfo['mobileNumber'];
                    $profile['school_name'] = $profileInfo['schoolName'];
                    $profile['grade'] = $profileInfo['grade'];
                    $profile['district'] = $profileInfo['district'];

                    $resp = $profile;
                }

                $userProfile = array();
                if ($resp) {
                    // $userProfile['id'] = $resp['id'];
                    $userProfile['userId'] = $resp['user_id'];
                    $userProfile['age'] = $resp['age'];
                    $userProfile['gender'] = $resp['sex'];
                    $userProfile['nicNumber'] = $resp['nic_number'];
                    $userProfile['mobileNumber'] = $resp['mobileNumber'];
                    $userProfile['schoolName'] = $resp['school_name'];
                    $userProfile['grade'] = $resp['grade'];
                    $userProfile['district'] = $resp['district'];

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $userProfile);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getUserProfileInfo(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($clientId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client id is required.");
        } else {    
            try {
                $resp = $this->Profile->query_find($clientId);

                $userProfile = array();
                if ($resp) {
                    $userProfile['id'] = $resp['id'];
                    $userProfile['userId'] = $resp['user_id'];
                    $userProfile['age'] = $resp['age'];
                    $userProfile['gender'] = $resp['sex'];
                    $userProfile['nicNumber'] = $resp['nic_number'];
                    $userProfile['mobileNumber'] = $resp['mobile_number'];
                    $userProfile['schoolName'] = $resp['school_name'];
                    $userProfile['grade'] = $resp['grade'];
                    $userProfile['district'] = $resp['district'];

                    return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $userProfile);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
