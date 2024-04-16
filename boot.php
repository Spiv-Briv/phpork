<?php declare(strict_types=1);

define('ROUTE_DEPTH',count(explode('/',$_SERVER['REQUEST_URI']))-3);
$path = './';
for($i=0;$i<ROUTE_DEPTH;$i++) {
    $path .= '../';
}
define('RELATIVE_PATH',$path);
define("ACCESSED_WITH_CLI", false);
$_ENV['RELATIVE_PATH'] = $path;


//shortcut functions
$css = [];
/** Attach css file. If css was already attached function returns empty string */
function css(string $file): string
{
    global $css;
    if(!in_array($file,$css)) {
        $css[] = $file;
        return "<link rel='stylesheet' href='".RELATIVE_PATH."Resources/css/".$file.".css' />";
    }
    return "";
}

/** Attach javascript file */
function js(string $file): string
{
    return "<script src='".RELATIVE_PATH."Resources/js/".$file.".js'></script>";
}

/** Include page located in Resources/pages folder */
function page(string $file, string $extension = 'php', bool $once = false): void
{
    if($once) {
        include_once RELATIVE_PATH."Resources/pages/$file.$extension";
    }
    else {
        include RELATIVE_PATH."Resources/pages/$file.$extension";
    }
}

/** Returns path relative from routes folder. You may specify extension (php by default) */
function route(string $file, string $extension = 'php'): string
{
    return RELATIVE_PATH."routes/$file.$extension";
}

/** Attach image located in Resources/images folder. Default image extension is 'png'. If $onlyFilePath is true, it will output filepath.
 * Otherwise (default) it shows image */
function image(string $file, string $extension = "png", bool $onlyFilepath = false): string
{
    if ($onlyFilepath) {
        return RELATIVE_PATH."Resources/images/$file.$extension";
    }
    else {
        return "<img alt='$file' src='".RELATIVE_PATH."Resources/images/$file.$extension' />";
    }
}

/**Attach icon located in Resources/icons folder. Default icon extensions is 'svg' but it can be changed. If $onlyFilePath is true, it will output filepath.
 * Otherwise (default) it shows icon.
 */
function icon(string $file, string $extension = "svg", bool $onlyFilePath = false): string
{
    if($onlyFilePath) {
        return RELATIVE_PATH."Resources/icons/$file.$extension";
    }
    else {
        return "<img alt='$file' src='".RELATIVE_PATH."Resources/icons/$file.$extension' />";
    }
}

function audio(string $file, string $extension = "mp3", bool $onlyFilePath = false): string
{
    if($onlyFilePath) {
        return RELATIVE_PATH."Resources/audio/$file.$extension";
    }
    else {
        return "<audio src='".RELATIVE_PATH."Resources/audio/$file.$extension' ></audio>";
    }
}

/** Use framework default page */
function frameworkPage(string $file, string $extension = "php", bool $once = false): void
{
    if($once) {
        include_once RELATIVE_PATH."Framework/$file.$extension";
    }
    else {
        include RELATIVE_PATH."Framework/$file.$extension";
    }
}

//Launch base css
echo css('root').
css('root').
css('root').
css('defaults/button').
css('defaults/container').
css('defaults/list').
css('defaults/tab_panel').
js('customElements/CustomElement').
js('customElements/TabPanel').
js('customElements/Panel').
js('customElements/postButton').
js('customElements/getButton').
js('customElements/Container').
js('customElements/List').
js('customElements/Category').
js('customElements/Item');

//Launch connections
require_once "Framework/EnvLoader.php";
require_once "Framework/Connection/QueryBuilder.php";
require_once "Framework/Connection/Connection.php";
require_once "Framework/Database/DatabaseBuilder.php";
require_once "Framework/Database/TableBuilder.php";
require_once "Framework/Exceptions/UndefinedPropertyException.php";
require_once "Framework/Exceptions/CastException.php";
require_once "Framework/Exceptions/NotNumericException.php";
require_once "Framework/Exceptions/UnknownCastException.php";
require_once "Framework/Exceptions/CollectionTypeNotMatchedException.php";
require_once "Framework/Exceptions/CollectionAlreadyRestrictedException.php";
require_once "Framework/Interfaces/SqlQueryCastable.php";
require_once "Framework/Connection/TypeCast.php";

//Launch App
require_once RELATIVE_PATH."/App/Collections/Collection.php";
foreach (scandir(RELATIVE_PATH.'/App/Collections') as $item) {
    if($item!='.'&&$item!='..') {
        require_once RELATIVE_PATH."/App/Collections/$item";
    }
}
require_once RELATIVE_PATH."/App/Models/Model.php";
foreach (scandir(RELATIVE_PATH.'/App/Models') as $item) {
    if($item!='.'&&$item!='..'&&$item!="Model") {
        require_once RELATIVE_PATH."/App/Models/$item";
    }
}
foreach (scandir(RELATIVE_PATH.'/App/Controllers') as $item) {
    if($item!='.'&&$item!='..') {
        require_once RELATIVE_PATH."/App/Controllers/$item";
    }
}
foreach (scandir(RELATIVE_PATH.'/App/Services') as $item) {
    if($item!='.'&&$item!='..') {
        if(is_dir(RELATIVE_PATH."/App/Services/$item")) {
            foreach (scandir(RELATIVE_PATH . "/App/Services/$item") as $subItem) {
                if($subItem!='.'&&$subItem!='..') {
                    require_once RELATIVE_PATH."/App/Services/$item/$subItem";
                }
            }
        } else {
            require_once RELATIVE_PATH."/App/Services/$item";
        }
    }
}

try {
    require_once RELATIVE_PATH."Framework/Database/sqli_connection.php";
}
catch (mysqli_sql_exception $e) {
    frameworkPage('pages/exception');
    exit();
}
?>
<script>
    document.addEventListener('DOMContentLoaded',()=>{
    document.getElementsByTagName('html')[0].setAttribute('theme',localStorage.getItem('theme'));
    document.getElementsByTagName('nav')[0].setAttribute('class',localStorage.getItem('nav_button'));
    });
</script>
