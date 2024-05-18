<?php declare(strict_types=1);

namespace App\Enums;

enum CollectionHeadingIdentifier: string {
    case INTRODUCTION = "introduction";
    case CREATE_COLLECTION = "create_collection";
    case PROPERTIES = "properties";
    case TYPE = "type";
    case ELEMENTS_PER_PAGE = "elements_per_page";
    case STATIC_METHODS = "static_methods";
    case METHODS = "methods";

    static function properties(): array
    {
        return [
            self::TYPE,
            self::ELEMENTS_PER_PAGE,
        ];
    }
}