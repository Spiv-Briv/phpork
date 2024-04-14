<?php declare(strict_types=1);

namespace App\Collections;

use Iterator;
use Countable;
use ArrayAccess;
use App\Models\Model;
use DivisionByZeroError;
use Framework\Exceptions\NotNumericException;
use Framework\Exceptions\UndefinedPropertyException;

class Collection implements Countable, Iterator, ArrayAccess
{

    const EQUALS = '=';
    const LESS = '<';
    const GREATER = '>';
    const LESS_OR_EQUAL = '<=';
    const GREATER_OR_EQUAL = '>=';
    const NOT_EQUALS = '!=';

    protected array $elements = [];
    protected int $position = 0;

    function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            $this->elements[] =  $element;
        }
    }

    /**
     * Return certain amount of collection elements.
     * @param ?int $length Amount of items to return. Null value and int greater than total amount of elements returns all avaiable.
     * @param ?int $offset Amount of items to skip from beggining of array. Null value is equivalent to 0. Int gretaer than total amount of elements returns empty collection.
     * @return static new Collection that is independend from collection where method was called
     */
    function get(?int $length = null, ?int $offset = null): ?self
    {
        $collectionName = get_called_class();
        $elements = $this->elements;
        if (is_null($offset) && is_null($length)) {
            return new $collectionName($elements);
        }
        if (!is_null($offset)) {
            for ($i = 0; $i < $offset; $i++) {
                array_shift($elements);
            }
        }
        if (!is_null($length) && $length < count($elements)) {
            while (count($elements) > $length) {
                array_pop($elements);
            }
        }
        return new $collectionName($elements);
    }

    /**
     * Return certain amount of collection elements.
     * @param ?int $length Amount of items to return. Null value and int greater than total amount of elements returns all avaiable.
     * @param ?int $offset Amount of items to skip from beggining of array. Null value is equivalent to 0. Int gretaer than total amount of elements returns empty collection.
     * @return static Collection where method was called
     */
    function limit(?int $length = null, ?int $offset = null): ?self
    {
        $elements = $this->elements;
        if (is_null($offset) && is_null($length)) {
            return $this;
        }
        if (!is_null($offset)) {
            for ($i = 0; $i < $offset; $i++) {
                array_shift($elements);
            }
        }
        if (!is_null($length) && $length < count($elements)) {
            while (count($elements) > $length) {
                array_pop($elements);
            }
        }
        $this->elements = $elements;
        $this->rewind();
        return $this;
    }

    /**
     * @param string $property Property used for comparison operation
     * @param string $operator Comparison type. Recommended usage one of collection constants. Unknown operation will be ignored
     * @param mixed $value Value to compare all models in collection
     * @return static Collection where method was called
     */
    function where(string $property, string $operator, mixed $value): ?self
    {
        $elements = $this->elements;
        for ($i = count($elements) - 1; $i >= 0; $i--) {
            switch ($operator) {
                case self::EQUALS: {
                        if ($elements[$i]->$property != $value) {
                            unset($elements[$i]);
                        }
                        break;
                    }
                case self::LESS: {
                        if ($elements[$i]->$property >= $value) {
                            unset($elements[$i]);
                        }
                        break;
                    }
                case self::LESS_OR_EQUAL: {
                        if ($elements[$i]->$property > $value) {
                            unset($elements[$i]);
                        }
                        break;
                    }
                case self::GREATER: {
                        if ($elements[$i]->$property <= $value) {
                            unset($elements[$i]);
                        }
                        break;
                    }
                case self::GREATER_OR_EQUAL: {
                        if ($elements[$i]->$property < $value) {
                            unset($elements[$i]);
                        }
                        break;
                    }
                case self::NOT_EQUALS: {
                        if ($elements[$i]->$property == $value) {
                            unset($elements[$i]);
                        }
                        break;
                    }
            }
        }
        $this->elements = array_values($elements);
        $this->rewind();
        return $this;
    }

    /**
     * Returns sum of given property from all models in collection.
     * @throws NotNumericException When at least one property value is not numeric
     * @throws UndefinedPropertyException When property is not defined in model
     * @param string $property Property name
     */
    function sum(string $property): float
    {
        if(empty($this->elements)) {
            return 0;
        }
        if(!in_array($property, $this->elements[0]::getColumns())) {
            throw new UndefinedPropertyException();
        }
        $sum = 0;
        foreach($this->elements as $element) {
            if(!is_numeric($element->$property)) {
                throw new NotNumericException();
            }
            $sum += $element->$property;
        }
        return $sum;
    }

    /**
     * Returns average of given property from all models in collection.
     * @throws NotNumericException When at least one property value is not numeric
     * @throws UndefinedPropertyException When property is not defined in model
     * @throws DivisionByZeroError When collection is empty
     * @param string $property Property name
     * @param ?int $round Number of decimal digits. Null means rounding is ommited.
     * @return float
     */
    function avg(string $property, ?int $round = null): float
    {
        if(empty($this->elements)) {
            throw new DivisionByZeroError();
        }
        if(!in_array($property, $this->elements[0]::getColumns())) {
            throw new UndefinedPropertyException();
        }
        if(is_null($round)) {
            return $this->sum($property)/count($this->elements);
        }
        return round($this->sum($property)/count($this->elements), $round);
    }

    /**
     * Sorts collection by given properties
     * @param string|array $properties 
     */
    function sort(string|array $properties = "id", bool|array $ascending = true): ?self
    {
        if (!is_array($properties)) {
            $properties = [$properties];
        }
        if (!is_array($ascending)) {
            $ascending = [$ascending];
        }
        usort($this->elements, function ($a, $b) use ($properties, $ascending) {
            $ascend_last = $ascending[array_key_last($ascending)];
            foreach ($properties as $property) {
                if (!isset($a->$property)) {
                    throw new UndefinedPropertyException();
                }
                if (count($ascending) > 0) {
                    $asc = array_shift($ascending);
                } else {
                    $asc = $ascend_last;
                }
                if (!$asc) {
                    $subA = $b;
                    $subB = $a;
                } else {
                    $subA = $a;
                    $subB = $b;
                }
                if ($subA->$property == $subB->$property) {
                    continue;
                }
                if ($subA->$property < $subB->$property) {
                    return -1;
                } else {
                    return 1;
                }
                return $subA->$property - $subB->$property;
            }
        });
        return $this;
    }

    function current(): Model
    {
        return $this->elements[$this->position];
    }

    function next(): void
    {
        $this->position++;
    }

    function key(): int
    {
        return $this->position;
    }

    function valid(): bool
    {
        return isset($this->elements[$this->position]);
    }

    function rewind(): void
    {
        $this->position = 0;
    }

    function offsetExists(mixed $offset): bool
    {
        return isset($this->elements[$offset]);
    }

    function offsetGet(mixed $offset): mixed
    {
        return $this->elements[$offset];
    }

    function offsetSet(mixed $offset, mixed $value): void
    {
        if (empty($offset)) {
            $this->elements[] = $value;
        } else {
            $this->elements[$offset] = $value;
        }
    }

    function offsetUnset(mixed $offset): void
    {
        unset($this->elements[$offset]);
    }

    function count(): int
    {
        return count($this->elements);
    }

    function __toString()
    {
        if (empty($this->elements)) {
            return 'Empty collection';
        }
        $table = $this->elements[0]::getTableName();
        $columns = $this->elements[0]::getColumns();
        $columnCount = count($columns);
        $superValues = [];
        $elementOffset = 0;
        foreach ($this->elements as $element) {
            $values = [];
            foreach (get_object_vars($element) as $index => $var) {
                if (is_a($var, 'DateTime')) {
                    if ($element::getType($index) == 'datetime') {
                        $values[] = "" . $var->format("Y-m-d H:i:s") . "";
                    } elseif ($element::getType($index) == "date") {
                        $values[] = "" . $var->format("Y-m-d") . "";
                    } elseif ($element::getType($index) == "time") {
                        $values[] = "" . $var->format("H:i:s") . "";
                    }
                } elseif (gettype($var) == 'array') {
                    $values[] = "[" . implode(',', $var) . "]";
                } else {
                    $values[] = "$var";
                }
            }
            $superValues[] = "<td style='padding: 10px; background-color: var(--color-secondary)'>" . $elementOffset++ . "</td><td style='padding: 10px;'>" . implode("</td><td style='padding: 10px;'>", $values) . "</td>";
        }
        $html = [
            "<table border='1' style='background-color: var(--color-secondary); margin: 5px; border-collapse: collapse; text-align: center;'>",
            "<thead style='background-color: var(--color-primary);'>",
            "<tr>",
            "<th style='padding: 10px' colspan='" . ($columnCount + 1) . "'> $table</th>",
            "</tr>",
            "</thead>",
            "<tbody>",
            "<tr>",
            "<td style='padding: 10px'>el.</td>",
            "<td style='padding: 10px'>",
            implode("</td><td style='padding: 10px'>", $columns),
            "</td>",
            "</tr>",
            "</tbody>",
            "<tfoot style='background-color: var(--color-third);'>",
            "<tr>",
            implode("</tr><tr>", $superValues),
            "</tr>",
            "</tfoot>",
            "</table>"
        ];
        return implode($html);
    }
}
