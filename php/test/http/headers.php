<?php
namespace kekse;
header('Content-Type: text/plain;charset=UTF-8');
require_once(__DIR__ . '/../kekse/connection.php');
$headers = Connection::headers();
var_dump($headers);
?>
