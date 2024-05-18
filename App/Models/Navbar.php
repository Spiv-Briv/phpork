<?php declare(strict_types=1);

namespace App\Models;

/**
* @property string id
* @property integer order
* @property string identifier
* @property string item
*/
class Navbar extends Model
{
    protected static string $table = "navbar";
    protected static  array $columns = [
		"id",
		"order",
        "identifier",
		"item",
	];
    protected static  array $types = [
        "order" => "int",
    ];
}