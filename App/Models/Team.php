<?php declare(strict_types=1);

namespace App\Models;

class Team extends Model {
    protected static string $table = "teams";
    protected static array $columns = [
        "id",
        "city",
        "sponsor",
        "trainer",
        "league",
        "country_id",
        "estabilished",
        "stadium_name",
        "colors",
    ];
    protected static array $types = [
        "country_id" => Country::class,
        "colors" => 'array'
    ];
    protected static string $linkedProperty = "id";
    // protected static ?array $stringTree = [
    //     "id",
    //     "sponsor",
    //     "city",
    //     "country_id" => "country_id.id",
    //     "country_shortcut" => "country_id.shortcut",
    // ];
}