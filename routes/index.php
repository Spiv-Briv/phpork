<?php

use App\Models\Country;
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
    $country = Country::find(1);
    echo $country;
    $country->name = "RZECZPOSPOLITA POLSKA";
    $country->save();
    echo "Updated";
    echo $country;
    echo "Modified";
    $country->name = "Nigger";
    $country->shortcut = "NIG";
    echo $country;
    echo "Loaded";
    echo $country->load();
    $country->update([
        'name' => "Polska",
        'shortcut' => "POL",
    ]);
    ?>
</body>
</html>