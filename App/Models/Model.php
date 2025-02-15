<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Interfaces\Comparable;
use Stringable;
use JsonSerializable;
use App\Collections\Collection;
use Framework\Connection\TypeCast;
use Framework\Connection\QueryBuilder;
use Framework\Exceptions\CastException;
use Framework\Interfaces\SqlQueryCastable;
use Framework\Exceptions\NotBooleanException;
use Framework\Exceptions\NotNumericException;
use Framework\Exceptions\UnknownCastException;
use Framework\Exceptions\UndefinedPropertyException;

abstract class Model implements Stringable, SqlQueryCastable, JsonSerializable, Comparable
{
    protected static string $table;
    protected static array $columns;
    protected static array $types = [];
    protected static string $linkedProperty = "id";
    protected static ?array $stringTree = null;
    protected static string $collection = Collection::class;

    /**
     * @return QueryBuilder
     */
    public static function query(): QueryBuilder
    {
        return new QueryBuilder(self::getTableName(), get_called_class());
    }

    /** 
     * Staticly called pagination function equivalent to Model::getAll()->page($page)
     * @param int $page number of page to return (first page is 0)
     * @return Collection
     */
    public static function page(int $page): Collection
    {
        return self::getAll()->page($page);
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
     *  @return Collection
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
     * @param mixed $id
     * @return static
     */
    public static function find(mixed $id): ?self
    {
        $query = self::query()->where(self::getLinkedProperty(), QueryBuilder::EQUALS, $id)->first();
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
        $query = self::query()->first();
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

    public function get(string $property): mixed
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        return $this->$property;
    }

    public function set(string $property, mixed $value): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if (!is_numeric($this->$property) && !is_a($value, $this->$property::class)) {
            throw new CastException("Casts are mismatched", 10005);
        }
        $this->$property = $value;
        return $this;
    }

    public function add(string $property, mixed $value): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if (!is_numeric($value) || !is_numeric($this->$property)) {
            throw new NotNumericException();
        }
        $this->$property += $value;
        return $this;
    }

    public function subtract(string $property, mixed $value): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if (!is_numeric($value) || !is_numeric($this->$property)) {
            throw new NotNumericException();
        }
        $this->$property -= $value;
        return $this;
    }

    public function multiply(string $property, mixed $value): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if (!is_numeric($value) || !is_numeric($this->$property)) {
            throw new NotNumericException();
        }
        $this->$property *= $value;
        return $this;
    }

    public function divide(string $property, mixed $value): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if (!is_numeric($value) || !is_numeric($this->$property)) {
            throw new NotNumericException();
        }
        $this->$property /= $value;
        return $this;
    }

    public function modulo(string $property, mixed $value): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if (!is_numeric($value) || !is_numeric($this->$property)) {
            throw new NotNumericException();
        }
        $this->$property %= $value;
        return $this;
    }

    public function toggle(string $property): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if (!is_bool($this->$property)) {
            throw new NotBooleanException();
        }
        $this->$property = !$this->$property;
        return $this;
    }

    public function increment(string $property): self
    {
        return $this->add($property, 1);
    }

    public function decrement(string $property): self
    {
        return $this->subtract($property, 1);
    }

    public function strReverse(string $property): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        $this->$property = strrev($this->$property);
        return $this;
    }

    public function strPad(string $property, int $length, string $padString = " ", int $padType = STR_PAD_RIGHT): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        $this->$property = str_pad($this->$property, $length, $padString, $padType);
        return $this;
    }

    public function subString(string $property, int $offset, ?int $length): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        $this->$property = substr($this->$property, $offset, $length);
        return $this;
    }

    public function arrayPop(string $property, int $length = 1): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if(!is_array($this->$property)) {
            throw new CastException("Property is not array", 10005);
        }
        if($length>count($this->$property)) {
            $this->$property = array();
            return $this;
        }
        for($i = 0; $i < $length; $i++) {
            array_pop($this->$property);
        }
        return $this;
    }

    public function arrayShift(string $property, int $length = 1): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if(!is_array($this->$property)) {
            throw new CastException("Property is not array", 10005);
        }
        if($length>count($this->$property)) {
            $this->$property = array();
            return $this;
        }
        for($i = 0; $i < $length; $i++) {
            array_shift($this->$property);
        }
        return $this;
    }

    public function arrayPush(string $property, mixed $value): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if(!is_array($this->$property)) {
            throw new CastException("Property is not array", 10005);
        }
        array_push($this->$property, $value);
        return $this;
    }

    public function arrayUnshift(string $property, mixed $value): self
    {
        if (!property_exists($this, $property)) {
            throw new UndefinedPropertyException();
        }
        if(!is_array($this->$property)) {
            throw new CastException("Property is not array", 10005);
        }
        array_push($this->$property, $value);
        return $this;
    }

    public function save(): bool
    {
        $data = [];
        foreach ($this->getColumns() as $column) {
            $value = $this->$column;
            if (is_array($value)) {
                $data[$column] = implode(',', $value);
            } elseif (is_a($value, 'DateTime')) {
                if (self::getType($column) == 'datetime') {
                    $data[$column] = $value->format("Y-m-d H:i:s");
                } elseif (self::getType($column) == "date") {
                    $data[$column] = $value->format("Y-m-d");
                } elseif (self::getType($column) == "time") {
                    $data[$column] = $value->format("H:i:s");
                }
            } elseif ($value instanceof Model) {
                $data[$column] = $value->toSqlString();
            } else {
                $data[$column] = $value;
            }
        }
        return $this->update($data);
    }

    public function load(): self
    {
        $property = self::getLinkedProperty();
        $model = self::find($this->$property);
        foreach ($model as $key => $value) {
            $this->$key = $value;
        }
        return $this;
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

    /** Returns assoc array of properties */
    function getValues(bool $json_compatible): array
    {
        $values = [];
        if (is_null(self::getStringTree())) {
            $columns = self::getColumns();
        } else {
            $columns = self::getStringTree();
        }
        foreach ($columns as $alias => $column) {
            if (is_numeric($alias)) {
                $alias = $column;
            }
            $variableNameArray = explode(".", $column);
            $variableName = $variableNameArray[0];
            $variable = $this->$variableName;
            if ($json_compatible) {
                $values[$alias] = $variable;
                continue;
            }
            if (is_a($variable, 'DateTime')) {
                if (self::getType($column) == 'datetime') {
                    $values[$alias] = "" . $variable->format("Y-m-d H:i:s") . "";
                } elseif (self::getType($column) == "date") {
                    $values[$alias] = "" . $variable->format("Y-m-d") . "";
                } elseif (self::getType($column) == "time") {
                    $values[$alias] = "" . $variable->format("H:i:s") . "";
                }
            } elseif ($this->$variableName instanceof Model && count($variableNameArray) > 1) {
                $variableProperty = $variableNameArray[1];
                $values[$alias] = $variable->$variableProperty;
            } elseif (gettype($variable) == 'array') {
                $values[$alias] = "[" . implode(',', $variable) . "]";
            } else {
                $values[$alias] = "$variable";
            }
        }
        return $values;
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

    function jsonSerialize(): mixed
    {
        return $this->getValues(true);
    }

    public function equals(Model $otherModel): bool
    {
        $linkedProperty = self::getLinkedProperty();
        return $this::class === $otherModel::class&&$this->$linkedProperty === $otherModel->$linkedProperty;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $table = self::getTableName();
        $values = $this->getValues(false);
        $columns = [];
        foreach ($values as $alias => $value) {
            $columns[] = $alias;
        }
        $columnCount = count($columns);
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
