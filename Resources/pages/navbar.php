<?= css('navbar') ?>
<nav>
    <?php

    use App\Models\Navbar;
    /** @var Navbar $item */
    foreach (Navbar::getAll() as $item) : ?>
        <a href="<?= route($item->identifier) ?>">
            <b><?= lang($item->item) ?></b>
        </a>
    <?php endforeach ?>
</nav>