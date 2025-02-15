<?php
/**
 * @var int $meeting
 * @var \App\Models\Schedule $match
 */

?>
<h2>Spotkanie nr <?= $meeting ?></h2>
<div><?= $match->home->id ?> - <?= $match->away->id ?></div>
