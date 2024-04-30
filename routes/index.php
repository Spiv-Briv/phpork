<?php

use App\Collections\Collection;
use App\Models\Country;
use App\Models\Schedule;
use App\Models\Team;

require_once "./../boot.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cope</title>
</head>
<body>
    <?php
    echo "Before update: ";
    $team = Team::find(3);
    $team->arrayShift('colors')->arrayPush('colors', '#FF0');
    echo $team;
    $newTeam = $team;
    $newTeam->load();
    echo $team;
    echo $newTeam;
    ?>
</body>
</html>