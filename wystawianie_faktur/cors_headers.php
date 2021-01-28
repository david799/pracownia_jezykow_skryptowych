<?php
$http_origin = $_SERVER['HTTP_ORIGIN'];

$origins_array = ["https://hyperionenergy.pl"];

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if (in_array($http_origin, $origins_array))
    {  
        header("Access-Control-Allow-Origin: $http_origin");
    }
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die();
}

if (in_array($http_origin, $origins_array))
{  
    header("Access-Control-Allow-Origin: $http_origin");
}
header('Content-Type: application/json');
?>