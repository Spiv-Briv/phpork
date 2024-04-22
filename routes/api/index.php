<?php

use App\Models\Team;

require_once "./../../boot.php";
echo json_encode(["method" => $_SERVER["REQUEST_METHOD"], "request" => $_REQUEST,"data" =>Team::page($_GET["page"])]);