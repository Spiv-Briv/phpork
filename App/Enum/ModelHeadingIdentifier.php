<?php declare(strict_types=1);

namespace App\Enums;

enum ModelHeadingIdentifier: string {
    case INTRODUCTION = "introduction";
    case CREATE_MODEL = "create_model";
    case TABLE = "table";
    case STATIC_PROPERTIES = "static_properties";
    case RESTRICT_COLUMNS = "restrict_columns";
    case PROPERTIES_TYPE = "properties_type";
    case LINKED_PROPERTY = "linked_property";
    case STRING_TREE = "string_tree";
    case COLLECTION = "collection";
    case STATIC_METHODS = "static_methods";
    case METHODS = "methods";

    static function properties(): array
    {
        return [
            self::TABLE,
            self::RESTRICT_COLUMNS,
            self::PROPERTIES_TYPE,
            self::LINKED_PROPERTY,
            self::STRING_TREE,
            self::COLLECTION,
        ];
    }
}