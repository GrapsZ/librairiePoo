<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require "./Classes/Autoloader.php";
Autoloader::register();

$core = new Core();
$core->initCore();
$kernel = Kernel::getInstance();

$stmt = Kernel::getInstance()->getConnection()->prepare("SELECT * FROM livres ORDER BY id");
$stmt->execute();

if ($stmt->rowcount() > 0) {
    $result = $stmt->fetchAll();
    $newTabCommands = json_encode($result, JSON_PRETTY_PRINT-JSON_UNESCAPED_UNICODE);
    exit($newTabCommands);
}
