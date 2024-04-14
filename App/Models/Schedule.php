<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Model;



class Schedule extends Model {
    protected static string $table = 'racer';
    protected static array $columns = [
        "id",
        "imie",
        "nazwisko",
        "wiek",
        "birthdate",
        "ocena",
        "country_id",
        "team_id",
        "mecze",
        "biegi",
        "places",
        "punkty",
        "bonusy",
        "d",
        "w",
        "t",
        "u"
    ];
    protected static array $types = [
        "birthdate" => "date",
        "places" => "int[]",
    ];
}