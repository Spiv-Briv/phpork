<?php global $e;
/** @var mysqli_sql_exception $e */ $i = 1; ?>
<div style="margin: auto; width: fit-content; margin-top: 100px; padding: 25px; background-color: var(--color-secondary)">
    <div style="font-size: larger; text-align: center;">
        <?= "Error <b style='color: #0CC'>{$e->getCode()}</b>: '<i style='color: #8F8;'>{$e->getMessage()}</i>'" ?>
    </div>
    <table border="1" style="margin: 15px; border-collapse: collapse">
        <thead style="backdrop-filter: brightness(150%);">
            <tr>
                <th style="padding: 10px;">Nr.</th>
                <th style="padding: 10px;">File</th>
                <th style="padding: 10px;">Function</th>
                <th style="padding: 10px;">Line</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($e->getTrace() as $key => $value) : ?>
                <?php
                $class = explode('\\', $value['file']);
                $args = [];
                if(isset($value["args"])) {
                    foreach ($value['args'] as $arg) {
                        if (is_object($arg)) {
                            $className = get_class($arg);
                            $args[] = "{<i style='color: #22A'>" . $className . "</i>}";
                        } elseif (is_array($arg)) {
                            $args[] = '[<i style="color: #8FF">' . implode('</i>, <i style="color: #8FF">', $arg) . '</i>]';
                        } elseif(is_string($arg)) {
                            $args[] = "<i style='color: #BFB'>\"$arg\"</i>";
                        }
                        else {
                            $args[] = "<i style='color: #BFB'>$arg</i>";
                        }
                    }
                }
                ?>
                <tr>
                    <td style="padding: 10px; backdrop-filter: brightness(125%);text-align: center;"><?= "$key." ?></td>
                    <td style="padding: 10px"><?= $value['file'] ?></td>

                    <td style="padding: 10px"><?= sprintf(
                                                    '<b style="color: #0DF">%s</b>::<i style="color: #FF0">%s</i>(%s);',
                                                    explode('.', end($class))[0],
                                                    $value['function'],
                                                    implode(', ', $args)
                                                ) ?></td>
                    <td style="padding: 10px; text-align: center;"><?= $value['line'] ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>