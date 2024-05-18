<?php

use App\Models\ModelHeading;

require_once "./../boot.php";
/** @var ModelHeadingCollection|ModelHeading[] $collection */
$collection = ModelHeading::sort('order', true);
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
            <ul>
                <?php foreach ($collection as $item) : ?>
                    <li>
                        <a href="#<?= $item->identifier ?>"><?= lang($item->title) ?></a>
                    </li>
                <?php endforeach ?>
            </ul>
        </aside>
        <section>
            <?= $collection->getAllContents() ?>
        </section>
    </main>
</body>

</html>