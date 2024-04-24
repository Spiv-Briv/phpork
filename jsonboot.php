<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
$rawBody = json_decode(file_get_contents('php://input'), true);
if(!is_null($rawBody)) {
    foreach($rawBody as $key => $value) {
        $_REQUEST[$key] = $value;
    }
}