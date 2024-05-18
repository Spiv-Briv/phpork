<?php declare(strict_types=1);

namespace App\Models;

/**
* @property string id
* @property ExampleMode example
*/
class AnotherModel extends Model
{
    protected static string $table = "second_table";
    protected static  array $columns = [
		"id",
		"example",
	];
    protected static  array $types = [
        "example" => ExampleModel::class,
    ];
}