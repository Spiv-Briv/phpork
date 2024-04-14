<?php

if (isset($_ENV['RELATIVE_PATH'])) {
    $file = file_get_contents($_ENV["RELATIVE_PATH"] . ".ENV");
} else {
    $file = file_get_contents(".ENV");
}
$file = rtrim($file);
$file = explode(PHP_EOL, $file);
foreach ($file as $line) {
    if(str_contains($line,"=")) {
        $arg = explode("=", $line);
        $_ENV[$arg[0]] = $arg[1];
    }
}
