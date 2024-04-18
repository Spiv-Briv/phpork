<?php

declare(strict_types=1);

namespace App\Models;

use Stringable;
use App\Collections\Collection;
use Framework\Connection\TypeCast;
use Framework\Connection\QueryBuilder;
use Framework\Exceptions\UndefinedPropertyException;
use Framework\Exceptions\UnknownCastException;
use Framework\Interfaces\SqlQueryCastable;

abstract class Model implements Stringable, SqlQueryCastable
{
    protected static string $table;
    protected static array $columns;
    protected static array $types = [];
    protected static string $linkedProperty = "id";
    protected static ?array $stringTree = null;
    protected static string $collection = "App\Collections\Collection";

    /**
     * @return QueryBuilder
     */
    public static function query(): QueryBuilder
    {
        return new QueryBuilder(self::getTableName(), get_called_class());
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return Collection
     */
    public static function where(string $column, string $operator, string $value): Collection
    {
        $collection = self::getCollectionObject();
        $rawCollection = self::query()->where($column, $operator, $value)->getAll();
        if (empty($rawCollection)) {
            return $collection;
        }
        foreach ($rawCollection as $item) {
            $collection[] = self::declareObject($item);
        }

        return $collection;
    }

    /**
     * @return Collection
     */
    public static function getAll(): Collection
    {
        $rawCollection = self::query()->getAll();
        $collection = self::getCollectionObject();
        if (empty($rawCollection)) {
            return $collection;
        }
        foreach ($rawCollection as $item) {
            $collection[] = self::declareObject($item);
        }
        return $collection;
    }

    /** @param string $column
     *  @param string $min
     *  @param string $max
     *  @return Colleciton
     */
    public static function between(string $column, string $min, string $max): Collection
    {
        $collection = self::getCollectionObject();
        $rawCollection = self::query()
            ->where($column, Collection::GREATER_OR_EQUAL, $min)
            ->where($column, Collection::LESS_OR_EQUAL, $max)
            ->getAll();
        if (empty($rawCollection)) {
            return $collection;
        }
        foreach ($rawCollection as $item) {
            $collection[] = self::declareObject($item);
        }

        return $collection;
    }

    /**
     * @param int $id
     * @return static
     */
    public static function find(int $id): ?self
    {
        $query = self::query()->where('id', QueryBuilder::EQUALS, (string)$id)->first();
        if (empty($query)) {
            return null;
        }
        return self::declareObject($query);
    }

    /**
     * @return static
     */
    public static function first(): ?self
    {
        $query = self::query()->where('id', QueryBuilder::GREATER_OR_EQUAL, '1')->first();
        if (empty($query)) {
            return null;
        }
        return self::declareObject($query);
    }

    /**
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return static
     */
    public static function firstWhere(string $column, string $operator, string $value): ?self
    {
        $query = self::query()->where($column, $operator, $value)->first();
        if (empty($query)) {
            return null;
        }
        return self::declareObject($query);
    }

    /**
     * @param array $data
     * @param bool $print
     * @return bool|string
     */
    public static function create(array $data, bool $print = false): bool|string
    {
        $query = self::query();
        if ($print) {
            return $query->createQuery($data);
        }
        return $query->create($data);
    }

    /**
     * @param array $data
     * @param bool $print
     * @return bool|string
     */
    public function update(array $data, bool $print = false): bool|string
    {
        $query = self::query();
        $linkedProperty = self::getLinkedProperty();
        if ($print) {
            return $query->updateQuery($this->$linkedProperty, $data, $linkedProperty);
        }
        return $query->update($this->$linkedProperty, $data, $linkedProperty);
    }

    /**
     * @param bool $print
     * @return bool|string
     */
    public function delete(bool $print = false): bool|string
    {
        $query = self::query();
        $linkedProperty = self::getLinkedProperty();
        if ($print) {
            return $query->deleteQuery($this->$linkedProperty, $linkedProperty);
        }
        return $query->delete($this->$linkedProperty, $linkedProperty);
    }

    private static function getCollectionObject(): Collection
    {
        $class = explode('\\', get_called_class() . "Collection");
        $class[1] = "Collections";
        $class = implode('\\', $class);
        if (!class_exists($class)) {
            $class = "App\Collections\Collection";
        }
        return new $class();
    }

    private static function getLinkedProperty(): string
    {
        return get_class_vars(get_called_class())["linkedProperty"];
    }

    public static function getType(string $column): string
    {
        return get_class_vars(get_called_class())["types"][$column];
    }

    public static function getColumns(): array
    {
        return get_class_vars(get_called_class())["columns"];
    }

    private static function declareObject(array $singleQuery): self
    {
        $columns = self::getColumns();
        $className = get_called_class();
        $object = new $className();
        $casts = get_class_vars(get_called_class())["types"];
        foreach ($columns as $column) {
            if (!array_key_exists($column, $singleQuery)) {
                throw new UndefinedPropertyException();
            }
            if (array_key_exists($column, $casts)) {
                switch (self::getType($column)) {
                    case 'bool': {
                            $object->$column = TypeCast::bool($singleQuery[$column]);
                            break;
                        }
                    case 'int': {
                            $object->$column = TypeCast::int($singleQuery[$column]);
                            break;
                        }
                    case 'float': {
                            $object->$column = TypeCast::float($singleQuery[$column]);
                            break;
                        }
                    case 'array':
                    case 'string[]': {
                            $object->$column = TypeCast::array($singleQuery[$column]);
                            break;
                        }
                    case 'string': {
                            $object->$column = TypeCast::string($singleQuery[$column]);
                            break;
                        }
                    case 'int[]': {
                            $object->$column = TypeCast::intArray($singleQuery[$column]);
                            break;
                        }
                    case 'float[]': {
                            $object->$column = TypeCast::floatArray($singleQuery[$column]);
                            break;
                        }
                    case 'bool[]': {
                            $object->$column = TypeCast::boolArray($singleQuery[$column]);
                        }
                    case 'datetime':
                    case 'time':
                    case 'date': {
                            $object->$column = TypeCast::datetime($singleQuery[$column]);
                            break;
                        }
                    default: {
                            if (class_exists($casts[$column])) {
                                $object->$column = TypeCast::model($singleQuery[$column], $casts[$column]);
                            } else {
                                throw new UnknownCastException("Defined cast doesn't exists", 1);
                            }
                        }
                }
            } elseif (is_numeric($singleQuery[$column])) {
                $object->$column = (int)$singleQuery[$column];
            } else {
                $object->$column = $singleQuery[$column];
            }
        }
        return $object;
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return get_class_vars(get_called_class())["table"];
    }

    public static function getStringTree(): ?array
    {
        return get_class_vars(get_called_class())["stringTree"];
    }

    function toSqlString(): string
    {
        $linkedProperty = self::getLinkedProperty();
        return "{$this->$linkedProperty}";
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $table = self::getTableName();
        if(is_null(self::getStringTree())) {
            $columns = self::getColumns();
        }
        else {
            $columns = self::getStringTree();
        }
        $columnCount = count($columns);
        $values = [];
        foreach ($columns as $column) {
            $variableNameArray = explode(".", $column);
            $variableName = $variableNameArray[0];
            $variable = $this->$variableName;
            if (is_a($variable, 'DateTime')) {
                echo "{$variableName} -> datetime<br/>";
                if (self::getType($column) == 'datetime') {
                    $values[] = "" . $variable->format("Y-m-d H:i:s") . "";
                } elseif (self::getType($column) == "date") {
                    $values[] = "" . $variable->format("Y-m-d") . "";
                } elseif (self::getType($column) == "time") {
                    $values[] = "" . $variable->format("H:i:s") . "";
                }
            } elseif($this->$variableName instanceof Model && count($variableNameArray)>1) {
                $variableProperty = $variableNameArray[1];
                $values[] = $variable->$variableProperty;
            } elseif (gettype($variable) == 'array') {
                $values[] = "[" . implode(',', $variable) . "]";
            } else {
                $values[] = "$variable";
            }
        }
        $html = [
            "<table border='1' style='background-color: var(--color-secondary); margin: 5px; border-collapse: collapse; text-align: center;'>",
            "<thead style='background-color: var(--color-primary);'>",
            "<tr>",
            "<th style='padding: 10px' colspan='$columnCount'>$table</th>",
            "</tr>",
            "</thead>",
            "<tbody>",
            "<tr>",
            "<td style='padding: 10px'>",
            implode("</td><td style='padding: 10px'>", $columns),
            "</td>",
            "</tr>",
            "</tbody>",
            "<tfoot style='background-color: var(--color-third);'>",
            "<tr>",
            "<td style='padding: 10px'>",
            implode("</td><td style='padding: 10px'>", $values),
            "</td>",
            "</tr>",
            "</tfoot>",
            "</table>"
        ];
        return implode($html);
    }
}
