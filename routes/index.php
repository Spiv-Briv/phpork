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
<?php for ($i = 1; $i <= 10; $i++)
    component('test_component', 'php',
        [
            "meeting" => $i,
            "match" => \App\Models\Schedule::find($i)
        ]
    );
?>
</body>
</html>