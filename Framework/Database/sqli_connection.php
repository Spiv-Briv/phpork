<?php

use Framework\Connection\Connection;
use Framework\Database\DatabaseBuilder;

$_ENV["mysqli"] = new Connection(
    $_ENV["MYSQLI_HOSTNAME"],
    $_ENV["MYSQLI_USERNAME"],
    $_ENV["MYSQLI_PASSWORD"],
    "",
);
if (!$_ENV["mysqli"]->setDatabase($_ENV["MYSQLI_DATABASE"])) {
    throw new mysqli_sql_exception("Unknown database '{$_ENV["MYSQLI_DATABASE"]}'",1049);
}
if(!$_ENV["mysqli"]->setTable("tables")) {
    if($_ENV["FORCE_CREATE_DATABASE"]!="create"&&$_ENV["FORCE_CREATE_DATABASE"]!="seed") {
        throw new mysqli_sql_exception("Database doesn't belong to this framework", 15000);
    }
    require_once RELATIVE_PATH."Framework/Terminal/Terminal.php";
    DatabaseBuilder::delete($_ENV["MYSQLI_DATABASE"]);
    DatabaseBuilder::create($_ENV["MYSQLI_DATABASE"]);
    if($_ENV["FORCE_CREATE_DATABASE"]=="seed") {
        DatabaseBuilder::seed($_ENV["mysqli"]->getDatabase());
    }
}
