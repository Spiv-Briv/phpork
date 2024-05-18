<?php declare(strict_types=1);

namespace App\Collections;

use App\Enums\ModelHeadingIdentifier;
use App\Models\ModelHeading;

class ModelHeadingCollection extends Collection
{
	protected ?string $type = ModelHeading::class;

	function getAllContents(): string
	{
		$return = '';
		foreach($this->elements as $element) {
			$return .= "
			<h2 id='{$element->identifier}'>".lang($element->title)."</h2><div>";
			switch ($element->identifier) {
				case ModelHeadingIdentifier::INTRODUCTION->value: {
					$return .= lang($element->content, ModelHeadingIdentifier::STATIC_METHODS->value);
					break;
				}
				case ModelHeadingIdentifier::CREATE_MODEL->value: {
					$return .= lang($element->content, route('cli'), 'model'); // TODO: change to CLIHEADINGIDENTIFIER::model
					break;
				}
				case ModelHeadingIdentifier::STATIC_PROPERTIES->value: {
					$subreturn = "";
					foreach(ModelHeadingIdentifier::properties() as $property) {
						switch ($property) {
							case ModelHeadingIdentifier::TABLE: {
								$columnName = "static string \$table";
								break;
							}
							case ModelHeadingIdentifier::RESTRICT_COLUMNS: {
								$columnName = "static array \$columns";
								break;
							}
							case ModelHeadingIdentifier::PROPERTIES_TYPE: {
								$columnName = "static array \$types";
								break;
							}
							case ModelHeadingIdentifier::LINKED_PROPERTY: {
								$columnName = "static string \$linkedProperty";
								break;
							}
							case ModelHeadingIdentifier::STRING_TREE: {
								$columnName = "static ?array \$stringTree";
								break;
							}
							case ModelHeadingIdentifier::COLLECTION: {
								$columnName = "static string \$collection";
								break;
							}
						}
						$subreturn .= lang("model.static_properties.list_item", $property->value, $columnName);
					}
					$return .= lang($element->content, $subreturn);
					break;
				}
				case ModelHeadingIdentifier::STATIC_METHODS->value: {
					$return .= lang($element->content, ModelHeadingIdentifier::LINKED_PROPERTY->value, mb_strtolower(lang("model.".ModelHeadingIdentifier::LINKED_PROPERTY->value.".title")));
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