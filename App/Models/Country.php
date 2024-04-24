<?php declare(strict_types=1);

namespace App\Models;

class Country extends Model
{
    protected static string $table = "countries";
    protected static array $columns = [
        "id",
        "name",
        "shortcut",
        "colors"
    ];
    protected static array $types = [
        "colors" => 'array'
    ];
}