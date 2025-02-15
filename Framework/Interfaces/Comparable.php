<?php

namespace Framework\Interfaces;

use App\Models\Model;

interface Comparable
{
    function equals(Model $otherModel): bool;
}