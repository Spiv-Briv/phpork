<?php require_once './../boot.php';

use App\Models\CollectionHeading;

/** @var CollectionHeadingCollection|CollectionHeading[] $collection */
$collection = CollectionHeading::sort('order')
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