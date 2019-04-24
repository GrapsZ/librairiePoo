<?php
include "./templates/header.php";
if (isset($_GET['action']) && isset($_GET['book_id']) && isset($_GET['user_id'])) {
    $action = Kernel::getInstance()->clean($_GET['action']);
    $book = Books::getBookById(intval($_GET['book_id']));
    $user = Users::getUserById(intval($_GET['user_id']));

    if ($action === 'remove') {
        Cart::getInstance()->removeAllBookInCart($user, $book);
        header('Location: mycart.php');
        exit();
    } elseif ($action === 'update_add') {
        Cart::getInstance()->addBookInCart($user, $book, 1);
        header('Location: mycart.php');
        exit();
    } elseif ($action === 'update_remove') {
        Cart::getInstance()->removeBookInCart($user, $book, 1);
        header('Location: mycart.php');
        exit();
    }
}
