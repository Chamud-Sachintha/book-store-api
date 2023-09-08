<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $Order;
    private $OrderItems;
    private $Apphelper;
    private $User;
    private $Cart;
    private $CartItems;

    public function __construct()
    {
        $this->Order = new Order();
        $this->Apphelper = new AppHelper();
        $this->User = new User();
        $this->Cart = new Cart();
        $this->OrderItems = new OrderItems();
        $this->CartItems = new CartItems();
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
            return $this->Apphelper->responseMessageHandle(0, "Cart id is Required.");
        } else {
            try {
                $verify_client = $this->User->find_by_id($clientId);
                $verify_cart = $this->Cart->verify_cart($clientId);

                if (!$verify_client) {
                    return $this->Apphelper->responseMessageHandle(0, "Invalid Client Id.");
                } else if (!$verify_cart) {
                    return $this->Apphelper->responseMessageHandle(0, "Invalid Cart Id.");
                } else {
                    try {
                        $clientOrderId = $this->createOrderForUser($clientId);

                        if ($clientOrderId) {
                            $allCartItems = DB::table('cart_items')->select('cart_items.quantity', 'books.id as bookId', 'books.book_name', 'books.book_cover', 'books.book_category_id', 'books.book_price', 'books.author_name', 'books.rating')
                                    ->join('books', 'cart_items.book_id', '=', 'books.id')
                                    ->join('carts', 'carts.client_id', '=', 'cart_items.cart_id')
                                    ->join('users', 'users.id', 'carts.client_id')
                                    ->where('users.id', '=', $clientId)
                                    ->get();

                            $orderItemsList = array();
                            foreach ($allCartItems as $key => $value) {
                                $orderItemsList[$key]['orderId'] = $clientOrderId;
                                $orderItemsList[$key]['bookId'] = $value->bookId;
                                $orderItemsList[$key]['quantity'] = $value->quantity;
                                $orderItemsList[$key]['time'] = $this->Apphelper->get_date_and_time();
                            }

                            $insertRespOrderInfo = $this->OrderItems->place_new_order_items($orderItemsList);

                            if ($insertRespOrderInfo) {
                                $removeCart = $this->Cart->remove_cart_for_user($clientId);
                                $removeCartItems = $this->CartItems->remove_cart_items_by_id($cartId);

                                if ($removeCart && $removeCartItems) {
                                    return $this->Apphelper->responseMessageHandle(0, "Operation Complete");
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        return $this->Apphelper->responseMessageHandle(0, $e->getMessage());
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
            $resp = $this->Order->verify_order($clientId);

            if ($resp) {
                $clientOrderId = $resp['id'];
            } else {
                $orderInfo = array();

                $orderInfo['clientId'] = $clientId;
                $orderInfo['payStatus'] = 1;
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
