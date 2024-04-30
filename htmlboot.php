<?php declare(strict_types=1);

//shortcut functions
$css = [];
/** Attach css file. If css was already attached function returns empty string */
function css(string $file): string
{
    global $css;
    if(!in_array($file,$css)) {
        $css[] = $file;
        return "<link rel='stylesheet' href='".RESOURCE_PATH."Resources/css/".$file.".css' />";
    }
    return "";
}

/** Attach javascript file */
function js(string $file): string
{
    return "<script src='".RESOURCE_PATH."Resources/js/".$file.".js'></script>";
}

/** Include page located in Resources/pages folder */
function page(string $file, string $extension = 'php', bool $once = false): void
{
    if($once) {
        include_once RESOURCE_PATH."Resources/pages/$file.$extension";
    }
    else {
        include RESOURCE_PATH."Resources/pages/$file.$extension";
    }
}

/** Returns path relative from routes folder. You may specify extension (php by default) */
function route(string $file, string $extension = 'php'): string
{
    return RESOURCE_PATH."routes/$file.$extension";
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
        return RESOURCE_PATH."Resources/icons/$file.$extension";
    }
    else {
        return "<img alt='$file' src='".RESOURCE_PATH."Resources/icons/$file.$extension' />";
    }
}

function audio(string $file, string $extension = "mp3", bool $onlyFilePath = false): string
{
    if($onlyFilePath) {
        return RESOURCE_PATH."Resources/audio/$file.$extension";
    }
    else {
        return "<audio src='".RESOURCE_PATH."Resources/audio/$file.$extension' ></audio>";
    }
}

/** Use framework default page */
function frameworkPage(string $file, string $extension = "php", bool $once = false): void
{
    if($once) {
        include_once RESOURCE_PATH."Framework/$file.$extension";
    }
    else {
        include RESOURCE_PATH."Framework/$file.$extension";
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
?>
<script>
    document.addEventListener('DOMContentLoaded',()=>{
    document.getElementsByTagName('html')[0].setAttribute('theme',localStorage.getItem('theme'));
    document.getElementsByTagName('nav')[0].setAttribute('class',localStorage.getItem('nav_button'));
    });
</script>