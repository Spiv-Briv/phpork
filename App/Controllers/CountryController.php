<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Country;

class CountryController
{
    static function find(int $id): Country
    {
        return Country::find($id);
    }

    static function create(array $data): string
    {
        return Country::create($data, true);
    }
}