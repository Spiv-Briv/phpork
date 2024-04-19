<?php

use App\Models\Team;

require_once "./../../boot.php";
echo json_encode(Team::first());