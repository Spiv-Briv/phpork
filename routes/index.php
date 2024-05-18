<?php

require_once "./../boot.php";
?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cope</title>
        <?php
    prepareCss();
    prepareJs();
    ?>
    <?= css('index') ?>
</head>

<body>
    <?php
    page('header');
    page('navbar');
    ?>
    <main>
        <aside>
            <a href='<?= route('phpork','zip') ?>'>Pobierz framework</a>
        </aside>
        <section>
            <h2>Witamy na stronie głównej frameworka PHPork</h2>
        </section>
    </main>
</body>

</html>