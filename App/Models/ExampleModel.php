<?php declare(strict_types=1);

namespace App\Models;

/**
* @property string id
*/
class ExampleModel extends Model
{
    protected static string $table = "example_table";
    protected static  array $columns = [
		"id",
	];
    protected static  array $types = [
        // TODO: Put your casts here
    ];
}