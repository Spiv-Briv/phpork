<?php declare(strict_types=1);

namespace App\Models;

/**
* @property string id
* @property int order
* @property string identifier
* @property string title
* @property string content
*/
class CollectionHeading extends Model
{
    protected static string $table = "collection_headings";
    protected static  array $columns = [
		"id",
		"order",
		"identifier",
		"title",
		"content",
	];
    protected static  array $types = [
        "order" => "int",
    ];
}