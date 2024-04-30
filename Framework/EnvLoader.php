<?php

$file = file_get_contents(RELATIVE_PATH . ".ENV");
$file = rtrim($file);
$file = explode(PHP_EOL, $file);
foreach ($file as $line) {
    if(str_contains($line,"=")) {
        $arg = explode("=", $line);
        $_ENV[$arg[0]] = $arg[1];
    }
}
