<?php
require_once './../../boot.php';

use App\Controllers\LangController;
use Framework\Connection\ApiResponse;

echo new ApiResponse('GET', ['key', 'lang'], LangController::class, 'word');
echo new ApiResponse('GET', ['key'], LangController::class, 'word2');