<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    private $AppHelper;
    private $User;
    private $Cart;
    private $CartItems;
    private $Book;
    
    public function __construct()
    {
        $this->AppHelper = new AppHelper();
        $this->Cart = new Cart();
        $this->User = new User();
        $this->Book = new Book();
        $this->CartItems = new CartItems();
    }

    public function addItemsToCart(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;
        $bookId = (is_null($request->bookId) || empty($request->bookId)) ? "" : $request->bookId;
        $quantity = (is_null($request->quantity) || empty($request->quantity)) ? "" : $request->quantity;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($clientId == "") {
            return $this->AppHelper->responseMessageHandle(0 ,"Client id is Required.");
        } else if ($bookId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Book id is required.");
        } else if ($quantity == "") {
            return $this->AppHelper->responseMessageHandle(0, "Quantity is Required.");
        } else {
            try {
                $verify_user = $this->User->find_by_id($clientId);
                $verify_book = $this->Book->query_find($bookId);

                if (!$verify_user) {
                    return $this->AppHelper->responseMessageHandle(0 ,"Invalid User Id");
                } else if (!$verify_book) {
                    return $this->AppHelper->responseMessageHandle(0, "Invalid Book Id.");
                } else {
                    $clientCartId = $this->createCartForUser($clientId);

                    if ($clientCartId) {
                        $cartItemsInfo = array();

                        $cartItemsInfo['cartId'] = $clientCartId;
                        $cartItemsInfo['bookId'] = $bookId;
                        $cartItemsInfo['quantity'] = $quantity;
                        $cartItemsInfo['time'] = $this->AppHelper->get_date_and_time();

                        $addCartItems = $this->CartItems->create_cart_items($cartItemsInfo);

                        if ($addCartItems) {
                            $cartItems = array();

                            $cartItems['id'] = $addCartItems['id'];
                            $cartItems['cartId'] = $addCartItems['cart_id'];
                            $cartItems['bookId'] = $addCartItems['book_id'];
                            $cartItems['quantity'] = $addCartItems['quantity'];
                            $cartItems['time'] = $addCartItems['time'];

                            return $this->AppHelper->responseEntityHandle(1, "Operation Complete", $cartItems);
                        }
                    }
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function getAllCartItems(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $clientId = (is_null($request->clientId) || empty($request->clientId)) ? "" : $request->clientId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Toke is Required.");
        } else if ($clientId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Client is is required.");
        } else {
            try {
                $getCartofUser = $this->Cart->verify_cart($clientId);
                
                if ($getCartofUser) {
                    $resp = DB::table('cart_items')->select('carts.id as cartId', 'cart_items.quantity', 'books.id as bookId', 'books.book_name', 'books.book_cover', 'books.book_category_id', 'books.book_price', 'books.author_name', 'books.rating')
                                ->join('books', 'cart_items.book_id', '=', 'books.id')
                                ->join('carts', 'carts.id', '=', 'cart_items.cart_id')
                                ->join('users', 'users.id', 'carts.client_id')
                                ->where('users.id', '=', $clientId)
                                ->get();

                    $cartItemsList = array();
                    $totalCartAmount = 0;
                    foreach ($resp as $key => $value) {
                        $cartItemsList['body'][$key]['bookId'] = $value->bookId;
                        $cartItemsList['body'][$key]['bookName'] = $value->book_name;
                        $cartItemsList['body'][$key]['authorName'] = $value->author_name;
                        $cartItemsList['body'][$key]['rating'] = $value->rating;
                        $cartItemsList['body'][$key]['bookPrice'] = $value->book_price;
                        $cartItemsList['body'][$key]['book_cover'] = $value->book_cover;

                        $totalCartAmount += $value->book_price;
                    }

                    $cartItemsList['cartId'] = $resp[0]->cartId;
                    $cartItemsList['cartAmount'] = $totalCartAmount;

                    return $this->AppHelper->responseEntityHandle(1, "Operating Complete", $cartItemsList);
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    public function removeCartItemById(Request $request) {

        $request_token = (is_null($request->token) || empty($request->token)) ? "" : $request->token;
        $cartId = (is_null($request->cartId) || empty($request->cartId)) ? "" : $request->cartId;
        $bookId = (is_null($request->bookId) || empty($request->bookId)) ? "" : $request->bookId;

        if ($request_token == "") {
            return $this->AppHelper->responseMessageHandle(0, "Token is required.");
        } else if ($cartId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Cart is is required.");
        } else if ($bookId == "") {
            return $this->AppHelper->responseMessageHandle(0, "Book id is required.");
        } else {
            try {   
                $itemInfo = array();
                $itemInfo['cartId'] = $cartId;
                $itemInfo['bookId'] = $bookId;

                $resp = $this->CartItems->remove_cart_item_by_id($itemInfo);

                if ($resp) {
                    $checkCartItems = $this->CartItems->check_cart_items($cartId);
                    if (count($checkCartItems) == 0) {
                        $removeCart = $this->Cart->remove_cart_by_cartId($cartId);

                        if ($removeCart) {
                            return $this->AppHelper->responseMessageHandle(1, "Operation Complete");
                        }
                    }
                }
            } catch (\Exception $e) {
                return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
            }
        }
    }

    private function createCartForUser($clientId) {
        $clientCartId = '';
        try {
            $resp = $this->Cart->verify_cart($clientId);

            if ($resp) {
                $clientCartId = $resp['id'];
            } else {
                $cartInfo = array();

                $cartInfo['clientId'] = $clientId;
                $cartInfo['time'] = $this->AppHelper->get_date_and_time();

                $newCart = $this->Cart->create_cart($cartInfo);

                $clientCartId = $newCart['id'];
            }
        } catch (\Exception $e) {
            return $this->AppHelper->responseMessageHandle(0, $e->getMessage());
        }

        return $clientCartId;
    }
}
