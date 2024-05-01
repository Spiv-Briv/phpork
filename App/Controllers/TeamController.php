<?php declare(strict_types=1);

namespace App\Controllers;

use App\Collections\Collection;
use App\Models\Team;

class TeamController
{
    static function getAll(): Collection
    {
        return Team::getAll();
    }

    static function get(int $page): Collection
    {
        return Team::page($page);
    }

    static function find(int $id): Team
    {
        return Team::find($id);
    }

    static function getItemInPage(int $id, int $page): Team
    {
        return Team::page($page)[$id];
    }

    static function update(int $id, string $colors): bool
    {
        return Team::find($id)->update([
            "colors" => $colors
        ]);
    }

    static function create(string $data): string
    {
        return Team::create(explode(',', $data), true);
    }

    static function delete(string $id): string
    {
        return Team::find($id)->delete(true);
    }
}