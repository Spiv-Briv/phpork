<?php declare(strict_types=1);

namespace App\Controllers;

class LangController
{
    static function word(string $lang, string $key): ?string
    {
        
        return strict_lang($key, $lang);
    }

    static function word2(string $key): ?string
    {
        return lang($key);
    }
}