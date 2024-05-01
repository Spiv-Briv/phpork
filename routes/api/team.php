<?php

use App\Controllers\TeamController;
use Framework\Connection\ApiResponse;

require_once "./../../boot.php";


echo new ApiResponse('GET', [], TeamController::class, 'getAll');
echo new ApiResponse('GET', ['page'], TeamController::class, 'get');
echo new ApiResponse('GET', ['id'], TeamController::class, 'find');
echo new ApiResponse('GET', ['id', 'page'], TeamController::class, 'getItemInPage');
echo new ApiResponse('PUT', ['id', 'colors'], TeamController::class, 'update');
echo new ApiResponse('POST', ['data'], TeamController::class, 'create');
echo new ApiResponse('DELETE', ['id'], TeamController::class, 'delete');