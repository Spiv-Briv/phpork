<?php

use Framework\Connection\Connection;
use Framework\Database\DatabaseBuilder;

$_ENV["mysqli"] = new Connection(
    $_ENV["MYSQLI_HOSTNAME"],
    $_ENV["MYSQLI_USERNAME"],
    $_ENV["MYSQLI_PASSWORD"],
    "",
);
if(SCRIPT_ORIGIN!='CLI') {
    $command = '';
}
if (!$_ENV["mysqli"]->setDatabase($_ENV["MYSQLI_DATABASE"])&&!$command=='database') {
    throw new mysqli_sql_exception("Unknown database '{$_ENV["MYSQLI_DATABASE"]}'",1049);
}
if(!$command=='database'&&!$_ENV["mysqli"]->setTable("tables")) {
    if($_ENV["FORCE_CREATE_DATABASE"]!="create"&&$_ENV["FORCE_CREATE_DATABASE"]!="seed") {
        throw new mysqli_sql_exception("Database doesn't belong to this framework", 15000);
    }
    DatabaseBuilder::delete($_ENV["MYSQLI_DATABASE"]);
    DatabaseBuilder::create($_ENV["MYSQLI_DATABASE"]);
    if($_ENV["FORCE_CREATE_DATABASE"]=="seed") {
        DatabaseBuilder::seed($_ENV["mysqli"]->getDatabase());
    }
}
