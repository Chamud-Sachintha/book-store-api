<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $Order;
    private $Apphelper;
    private $User;
    private $Cart;

    public function __construct()
    {
        $this->Order = new Order();
        $this->Apphelper = new AppHelper();
        $this->User = new User();
        $this->Cart = new Cart();
    }

    public function placeNewOrder(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;
        $cartId = (is_null($request->cartId) || empty($request->cartId)) ? "" : $request->cartId;

        if ($request_token == "") {
            return $this->Apphelper->responseMessageHandle(0, "Token is Required.");
        } else if ($clientId == "") {
            return $this->Apphelper->responseMessageHandle(0, "Client id is required.");
        } else if ($cartId == "") {
            return $this->Apphelper->responseMessageHandle(0, "Order id is Required.");
        } else {
            try {
                $verify_client = $this->User->find_by_id($clientId);
                $verify_cart = $this->Cart->verify_cart($clientId);

                if (!$verify_client) {
                    return $this->Apphelper->responseMessageHandle(0, "Invalid Client Id.");
                } else if (!$verify_cart) {
                    return $this->Apphelper->responseMessageHandle(0, "Invalid Cart Id.");
                } else {
                    $clientOrderId = $this->createOrderForUser($clientId);

                    if ($clientOrderId) {

                    }
                }
            } catch (\Exception $e) {
                return $this->Apphelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getAllOrdersFromUser() {
        
    }

    private function createOrderForUser($clientId) {
        $clientOrderId = '';
        try {
            $resp = $this->Order->verify_cart($clientId);

            if ($resp) {
                $clientOrderId = $resp['id'];
            } else {
                $orderInfo = array();

                $orderInfo['clientId'] = $clientId;
                $orderInfo['time'] = $this->Apphelper->get_date_and_time();

                $newOrder = $this->Order->place_order($orderInfo);

                $clientOrderId = $newOrder['id'];
            }
        } catch (\Exception $e) {
            return $this->Apphelper->responseMessageHandle(0, $e->getMessage());
        }

        return $clientOrderId;
    }
}
