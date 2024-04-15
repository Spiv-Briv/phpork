<?php declare(strict_types=1);

namespace Framework\Interfaces;

interface SqlQueryCastable
{
    function toSqlString(): string;
}