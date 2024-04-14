<?php declare(strict_types=1);

namespace Framework\Connection;

use DateTime;
use Framework\Exceptions\CastException;

class TypeCast {
    static function int(string $variable): int
    {
        if(!is_numeric($variable)) {
            throw new CastException("Variable couldn't be converted to int", 10001);
        }
        return (int)$variable;
    }

    static function float(string $variable): float
    {
        if(!is_float($variable)&&!is_numeric($variable)) {
            throw new CastException("Variable couldn't be converted to float", 10002);
        }
        return (float)$variable;
    }

    static function string(string $variable): string
    {
        return $variable;
    }

    static function floatArray(string $variable): array
    {
        $array = explode(',',$variable);
        $newArray = [];
        foreach($array as $item) {
            $newArray[] = self::float($item);
        }
        return $newArray;
    }

    static function intArray(string $variable): array
    {
        $array = explode(',',$variable);
        $newArray = [];
        foreach($array as $item) {
            $newArray[] = self::int($item);
        }
        return $newArray;
    }

    static function array(string $variable): array
    {
        return explode(',',$variable);
    }

    static function datetime(string $variable): DateTime
    {
        return new DateTime($variable);
    }
}