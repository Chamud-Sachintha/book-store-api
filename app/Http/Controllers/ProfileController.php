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

    }
}
