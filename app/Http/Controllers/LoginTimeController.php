<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\LoginTime;
use Illuminate\Http\Request;

class LoginTimeController extends Controller
{

    private $LoginTime;
    private $AppHelper;

    public function __construct()
    {
        $this->LoginTime = new LoginTime();
        $this->AppHelper = new AppHelper();
    }

    public function addLoginTime(Request $request) {

        date_default_timezone_set("UTC");
        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;

        if ($clientId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client Id is required.");
        } else {
            try {
                $loginTime = strtotime(date("Y:m:d H:i:s"));
                $getTimeInfo = $this->LoginTime->get_time_info($clientId);

                $timeInfo = array();
                $timeInfo['clientId'] = $clientId;
                $timeInfo['loginTime'] = $loginTime;

                $resp = null;
                if ($getTimeInfo) {
                    $timeInfo['loginCount'] = $getTimeInfo['login_count'] + 1;
                    $resp = $this->LoginTime->update_login_time($timeInfo);
                } else {
                    $timeInfo['loginCount'] = 1;
                    $resp = $this->LoginTime->add_log($timeInfo);
                }

                if ($resp) {
                    return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                }
                
                // $from_time = $to_time + 3600;
                // dd(round(abs($to_time - $from_time) / 60,2). " minute");
                // dd(date("Y:m:d H:i:s"));
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function updateLogOutTime(request $request) {

        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;

        if ($clientId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client id is required.");
        } else {
            try {
                
                $getTimeInfo = $this->LoginTime->get_time_info($clientId);
                $logOutTime = strtotime(date("Y:m:d H:i:s"));

                $timeInfo = array();
                $timeInfo['clientId'] = $clientId;
                $timeInfo['logOutTime'] = $logOutTime;

                $timeInfo['timeDiff']  = $getTimeInfo->time_diff + ($logOutTime - $getTimeInfo->login_time);

                $resp = $this->LoginTime->update_logout_time($timeInfo);

                if ($resp) {
                    return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }
}
