<?php declare(strict_types=1);

define('ROUTE_DEPTH',count(explode('/',$_SERVER['SCRIPT_NAME']))-3);
$path = './';
for($i=0;$i<ROUTE_DEPTH;$i++) {
    $path .= '../';
}
define('RELATIVE_PATH',$path);

function strict_lang(string $key, string $lang, ... $items): ?string
{
    $key = explode('.', $key);
    $file = array_shift($key).".php";
    if(file_exists(RELATIVE_PATH."Languages/$lang/$file")) {
        $langTable = include RELATIVE_PATH."Languages/$lang/$file";
    }
    elseif(file_exists(RELATIVE_PATH."Languages/".TRANSLATION_LANGUAGE."/$file")) {
        $langTable = include RELATIVE_PATH."Languages/".TRANSLATION_LANGUAGE."/$file";
    }
    elseif(file_exists(RELATIVE_PATH."Languages/{$_ENV['LANGUAGE_DEFAULT']}/$file")) {
        $langTable = include RELATIVE_PATH."Languages/{$_ENV['LANGUAGE_DEFAULT']}/$file";
    }
    else {
        return null;
    }
    $langWord = $langTable;
    foreach($key as $item) {
        if(!array_key_exists($item, $langWord)) {
            return implode('.', $key);
        }
        $langWord = $langWord[$item];
    }
    try {
        $sentence = vsprintf($langWord, $items);
        return $sentence;
    }
    catch(ValueError|TypeError $e) {
        return implode('.', $key);
    }
}

function lang(string $key, ... $items): ?string
{
    $key = explode('.', $key);
    $file = array_shift($key).".php";
    if(file_exists(RELATIVE_PATH."Languages/".TRANSLATION_LANGUAGE."/$file")) {
        $langTable = include RELATIVE_PATH."Languages/".TRANSLATION_LANGUAGE."/$file";
    }
    elseif(file_exists(RELATIVE_PATH."Languages/{$_ENV['LANGUAGE_DEFAULT']}/$file")) {
        $langTable = include RELATIVE_PATH."Languages/{$_ENV['LANGUAGE_DEFAULT']}/$file";
    }
    else {
        return null;
    }
    $langWord = $langTable;
    foreach($key as $item) {
        if(!array_key_exists($item, $langWord)) {
            return implode('.', $key);
        }
        $langWord = $langWord[$item];
    }
    try {
        $sentence = vsprintf($langWord, $items);
        return $sentence;
    }
    catch(ValueError|TypeError $e) {
        return implode('.', $key);
    }
}

function loadDir(string $directory, array $ignorefiles = [], string $path = "") {
    $path .= $directory;
    if(in_array($path, $ignorefiles)) {
        return;
    }
    if(!is_dir($path)) {
        require_once $path;
        return;
    }
    foreach(scandir($path) as $file) {
        if($file!="."&&$file!=".."&&$file!=".gitignore") {
            if(is_dir($path."/".$file)) {
                loadDir($file, $ignorefiles, $path."/");
            }
            elseif(!in_array($path."/".$file, $ignorefiles)) {
                require_once $path."/".$file;
            }
        }
    }
}

loadDir(RELATIVE_PATH."Framework/EnvLoader.php");
loadDir(RELATIVE_PATH."Framework", [
    RELATIVE_PATH."Framework/EnvLoader.php",
    RELATIVE_PATH."Framework/Database/Seeders",
    RELATIVE_PATH."Framework/pages",
    RELATIVE_PATH."Framework/Terminal/Patterns",
    RELATIVE_PATH."Framework/Database/sqli_connection.php",
]);
loadDir(RELATIVE_PATH."App/Models/Model.php");
loadDir(RELATIVE_PATH."App/Collections/Collection.php");
loadDir(RELATIVE_PATH."App", [
    RELATIVE_PATH."App/Models/Model.php",
    RELATIVE_PATH."App/Collections/Collection.php"
]);
if($_SERVER['PHP_SELF']=="cli") {
    define('SCRIPT_ORIGIN', 'CLI');
    require_once "cliboot.php";
}
elseif(explode('/', $_SERVER["PHP_SELF"])[2]=="routes") {
    if(explode('/', $_SERVER["PHP_SELF"])[3]=="api") {
        define('SCRIPT_ORIGIN','JSON');
        require_once "jsonboot.php";
    }
    else {
        define('SCRIPT_ORIGIN',"HTML");
        require_once "htmlboot.php";
    }
}
try {
    require_once RELATIVE_PATH."Framework/Database/sqli_connection.php";
}
catch (mysqli_sql_exception $e) {
    if(SCRIPT_ORIGIN=="HTML") {
        frameworkPage('pages/exception');
    }
    elseif(SCRIPT_ORIGIN=="JSON") {
        echo json_encode([$e->getMessage()]);
    }
    else {
        echo $e;
    }
    exit();
}
?>
