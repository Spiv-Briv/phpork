<?php

use App\Models\Country;
use App\Models\Team;

require_once "./../boot.php";

echo Team::getAll();