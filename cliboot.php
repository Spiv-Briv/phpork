<?php

use Framework\Terminal\Terminal;

$file = array_shift($argv);
$argc--;
$availableCommands = ["database","table"];
if($argc==0) {
    echo Terminal::error("Unspecified command.").
    Terminal::warning("Available commands are: ".Terminal::variable(implode(', ',$availableCommands), Terminal::YELLOW));
    exit();
}
$command = array_shift($argv);
$argc--;
$arguments = parse($argv);

function parse(array $args): array
{
    $arguments = [
        "function" => "",
        "flags" => [],
        "params" => []
    ];
    foreach($args as $arg) {
        if(str_starts_with($arg, "--")) {
            $arguments["flags"][] = ltrim($arg, "-");
        }
        elseif(str_starts_with($arg, "-")) {
            $argval = explode(":", ltrim($arg, "-"));
            $arguments["params"][$argval[0]] = $argval[1];
        }
        elseif(!empty($arguments["function"])) {
            throw new Exception("Function has already been defined");
        }
        else {
            $arguments["function"] = $arg;
        }
    }
    return $arguments;
}

function getParam(array $params, string $parameter, mixed $default): mixed
{
    if(!isset($params[$parameter])) {
        return $default;
    }
    return $params[$parameter];
}