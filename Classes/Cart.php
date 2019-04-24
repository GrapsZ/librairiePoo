<?php


class Cart {

    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * @return Cart|null
     */
    public static function getInstance() {
        if (is_null(Cart::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    CONST CART_FILE = './Includes/myCart.json';

    /**
     * @param $user
     * @param $book
     * @param $quantity
     */
    public function addBookInCart($user, $book, $quantity){
        if (isset($book) && isset($quantity) && isset($user)) {
            if (file_exists(self::CART_FILE)) {
                $jsonFile = file_get_contents(self::CART_FILE);
                $cartTab = json_decode($jsonFile, true);

                $userFound = false;
                foreach ($cartTab as $key => $value) {
                    if ($value['user_id'] == $user->getId() && $value['book_id'] == $book->getId()) {
                        $userFound = true;

                        $cartTab[$key]['count'] = intval($value['count']) + $quantity;
                        $newCartTab = json_encode($cartTab, JSON_PRETTY_PRINT);
                        file_put_contents(self::CART_FILE, $newCartTab);
                        break;
                    }
                }
                if (!$userFound) {
                    $userTab = [
                        'user_id' => $user->getId(),
                        'book_id' => $book->getId(),
                        'count' => $quantity
                    ];
                    array_push($cartTab, $userTab);
                    $newCartTab = json_encode($cartTab, JSON_PRETTY_PRINT);
                    file_put_contents(self::CART_FILE, $newCartTab);
                }
            }
        }
    }

    /**
     * @param $user
     * @param $book
     * @param $quantity
     */
    public function removeBookInCart($user, $book, $quantity){
        if (isset($book) && isset($quantity) && isset($user)) {
            if (file_exists(self::CART_FILE)) {
                $jsonFile = file_get_contents(self::CART_FILE);
                $cartTab = json_decode($jsonFile, true);

                foreach ($cartTab as $key => $value) {
                    if ($value['user_id'] == $user->getId() && $value['book_id'] == $book->getId()) {
                        $count = $value['count'];
                        $countCalc = $count - $quantity;

                        if ($countCalc >= 1) {
                            $cartTab[$key]['count'] = $countCalc;
                        } else {
                            unset($cartTab[$key]);
                        }
                        $newCartTab = json_encode($cartTab, JSON_PRETTY_PRINT);
                        file_put_contents(self::CART_FILE, $newCartTab);
                        break;
                    }
                }
            }
        }
    }

    /**
     * @param $user
     * @param $book
     */
    public function removeAllBookInCart($user, $book){
        if (isset($book) && isset($user)) {
            if (file_exists(self::CART_FILE)) {
                $jsonFile = file_get_contents(self::CART_FILE);
                $cartTab = json_decode($jsonFile, true);

                foreach ($cartTab as $key => $value) {
                    if ($value['user_id'] == $user->getId() && $value['book_id'] == $book->getId()) {
                        unset($cartTab[$key]);
                        $newCartTab = json_encode($cartTab, JSON_PRETTY_PRINT);
                        file_put_contents(self::CART_FILE, $newCartTab);
                        break;
                    }
                }
            }
        }
    }

    /**
     * @param Users $user
     */
    public function deleteCartById(Users $user) {
        if (isset($user)) {
            if (file_exists(self::CART_FILE)) {
                $cartJson = file_get_contents(self::CART_FILE);
                $tabCart =  json_decode($cartJson, true);

                foreach ($tabCart as $key => $value) {
                    if ($user->getId() == $value['user_id']) {
                        unset($tabCart[$key]);
                    }
                }
                $newCartTab = json_encode($tabCart, JSON_PRETTY_PRINT);
                file_put_contents(self::CART_FILE, $newCartTab);
            }
        }
    }

    /**
     * @param $user
     * @return array
     */
    public function getUserCart($user){
        $userCart = [];
        if (isset($user)) {
            if (file_exists(self::CART_FILE)) {
                $jsonFile = file_get_contents(self::CART_FILE);
                $cartTab = json_decode($jsonFile, true);

                foreach ($cartTab as $key => $value) {
                    if ($value['user_id'] == $user->getId()) {
                        $book = [
                            'book_id' => $value['book_id'],
                            'count' => $value['count']
                        ];
                        array_push($userCart, $book);
                    }
                }
            }
        }
        return $userCart;
    }
}