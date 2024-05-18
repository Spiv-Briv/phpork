<?php declare(strict_types=1);

namespace App\Collections;

use App\Enums\CollectionHeadingIdentifier;
use App\Models\CollectionHeading;

class CollectionHeadingCollection extends Collection
{
	protected ?string $type = CollectionHeading::class;

	function getAllContents(): string
	{
		$return = '';
		foreach($this->elements as $element) {
			$return .= "
			<h2 id='{$element->identifier}'>".lang($element->title)."</h2><div>";
			switch ($element->identifier) {
			// 	case ModelHeadingIdentifier::INTRODUCTION->value: {
			// 		$return .= lang($element->content, ModelHeadingIdentifier::STATIC_METHODS->value);
			// 		break;
			// 	}
				case CollectionHeadingIdentifier::CREATE_COLLECTION->value: {
					$return .= lang($element->content, route('cli'), 'collection'); // TODO: change to CLIHEADINGIDENTIFIER::collection
					break;
				}
				case CollectionHeadingIdentifier::PROPERTIES->value: {
					$subreturn = "";
					foreach(CollectionHeadingIdentifier::properties() as $property) {
						switch ($property) {
							case CollectionHeadingIdentifier::TYPE: {
								$columnName = "string \$type";
								break;
							}
							case CollectionHeadingIdentifier::ELEMENTS_PER_PAGE: {
								$columnName = "int \$elementsPerPage";
								break;
							}
						}
						$subreturn .= lang("collection.properties.list_item", $property->value, $columnName);
					}
					$return .= lang($element->content, $subreturn);
					break;
				}
				default: {
					$return .= lang($element->content);
				}
			}
			$return .= "</div>";
		}
		return $return;
	}
}