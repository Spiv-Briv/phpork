<?php declare(strict_types=1);

namespace App\Models;

/**
* @property string id
* @property string league
* @property string round
* @property DateTime date
* @property Team home
* @property Team away
* @property string home_result
* @property string away_result
* @property string viewers
* @property string status
* @property string season_state
*/
class Schedule extends Model
{
    protected static string $table = "schedules";
    protected static  array $columns = [
		"id",
		"league",
		"round",
		"date",
		"home",
		"away",
		"home_result",
		"away_result",
		"viewers",
		"status",
		"season_state",
	];
    protected static  array $types = [
		'round' => "int",
        "date" => "date",
		"home" => Team::class,
		"away" => Team::class,
    ];
	protected static ?array $stringTree = [
		"id",
		"league",
		"round",
		"date",
		"home" => "home.city",
		"away" => "away.city",
		"home_result",
		"away_result",
		"viewers",
		"status",
		"season_state",
	];
}