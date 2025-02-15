<?php


require_once "./../boot.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cope</title>
</head>
<body>
<?= \App\Models\Schedule::find(1) ?>
<?= \App\Models\Schedule::find(2) ?>
<?php
$schedule = \App\Models\Schedule::find(1);
$schedule2 = \App\Models\Team::find(1);
$time = microtime(true);
if ($schedule == $schedule2) {

}
$stop = microtime(true);
$time = $stop - $time;
echo $time."<br/>";

$time = microtime(true);
if ($schedule->id===$schedule2->id) {

}
$stop = microtime(true);
$time = $stop - $time;
echo $time."<br/>";

$time = microtime(true);
if ($schedule->equals($schedule2)) {

}
$stop = microtime(true);
$time = $stop - $time;
echo $time."<br/>";
var_dump($schedule->equals($schedule2));
?>
</body>
</html>