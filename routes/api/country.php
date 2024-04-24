<?php

use App\Controllers\CountryController;
use Framework\Connection\ApiResponse;

 require_once './../../boot.php';

echo new ApiResponse('GET',['id'], CountryController::class, 'find');
echo new ApiResponse('POST', ['data'], CountryController::class, 'create');