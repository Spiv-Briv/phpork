<?php declare(strict_types=1);

namespace App\Models;

use App\Collections\ModelHeadingCollection;

/**
* @property int order
* @property string identifier
* @property string title
* @property string content
*/
class ModelHeading extends Model
{
    protected static string $table = "model_headings";
    protected static  array $columns = [
		"order",
        "identifier",
		"title",
		"content",
	];
    protected static  array $types = [
        "order" => "int",
    ];
    protected static string $collection = ModelHeadingCollection::class;
}