<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require "./Classes/Autoloader.php";
Autoloader::register();

$core = new Core();
$core->initCore();
$kernel = Kernel::getInstance();

if (!Users::isConnected()) {
    if (isset($_COOKIE['myLibrary']) && $_COOKIE['myLibrary'] != 'deconnecte') {
        $cookie = Kernel::getInstance()->clean($_COOKIE['myLibrary']);
        Users::connectUserByCookie($cookie);
    }
}

Kernel::getInstance()->setTitle('Librairie des beaux arts');
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Michael THAIZE">
    <meta name="generator" content="Jekyll v3.8.5">
    <title><?php echo $kernel->getTitle()?></title>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/album/">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">

    <!-- Bootstrap core CSS -->
    <link href="./Assets/css/bootstrap.min.css" rel="stylesheet">


    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="./Assets/css/app.css" rel="stylesheet">
</head>
<body>
