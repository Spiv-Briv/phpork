<?php declare(strict_types=1);

namespace Framework\Connection;

use App\Collections\Collection;
use Framework\Interfaces\SqlQueryCastable;

class QueryBuilder {
    const EQUALS = '=';
    const LESS = '<';
    const GREATER = '>';
    const LESS_OR_EQUAL = '<=';
    const GREATER_OR_EQUAL = '>=';
    const NOT_EQUALS = '!=';
    const LIKE = "LIKE";

    private string $table;
    private array $columns;
    private array $where;
    private array $whereGroups;
    private array $groups;
    private array $orders;
    private ?string $modelName;

    function __construct(string $table, ?string $modelName)
    {
        $this->table = $table;
        $this->modelName = $modelName;
    }

    function column(string $columnName, ?string $alias = null): QueryBuilder
    {
        if(!str_contains($columnName,'(')) {
            $columnName = "`$columnName`";
        }
        if(is_null($alias)) {
            $this->columns[] = "$columnName";
        }
        else {
            $this->columns[] = "$columnName AS '$alias'";
        }
        return $this;
    }

    function columns(array $columnNames): QueryBuilder
    {
        foreach($columnNames as $columnName) {
            $this->columns[] = $columnName;
        }
        return $this;
    }

    function where(string $columnName, string $operator, string|int $value, string $condition = "AND"): QueryBuilder
    {
        $this->where[] = [
            "clause" => "`$columnName` $operator '$value'",
            "condition" => $condition,
        ];
        return $this;
    }

    function orWhere(string $columnName, string $operator, string|int $value): QueryBuilder
    {
        return $this->where($columnName, $operator, $value, "OR");
    }

    function groupWhere(int $start, int $end): QueryBuilder
    {
        $this->whereGroups[] = [$start, $end];
        return $this;
    }

    function group(string $columnName): QueryBuilder
    {
        $this->groups[] = "`$columnName`";
        return $this;
    }

    function order(string $columnName, string $orderType = "ASC"): QueryBuilder
    {
        $this->orders[] = "`$columnName` $orderType";
        return $this;
    }

    function orderDesc(string $columnName): QueryBuilder
    {
        return $this->order($columnName, "DESC");
    }

    function get(?int $length = null, ?int $rowOffset = null): array
    {
        return $this->executeSelectQuery($this->parseSelectQuery($length, $rowOffset));
    }

    function first(): array
    {
        return $this->executeSelectQuery($this->parseSelectQuery(1));
    }

    function getAll(): array
    {
        return $this->executeSelectQuery($this->parseSelectQuery());
    }

    function toCollection(): Collection
    {
        $collection = new Collection();
        foreach($this->getAll() as $item) {
            echo $this->modelName::find((int)$item["id"]);
            $collection[] = $this->modelName::find((int)$item["id"]);
        }
        return $collection;
    }

    private function parseWhereGroups(): void
    {
        if(empty($this->whereGroups)){
            return;
        }
        foreach($this->whereGroups as $group) {
            $this->where[$group[0]-1]["clause"] = "(".$this->where[$group[0]-1]["clause"];
            $this->where[$group[1]-1]["clause"] .= ")";
        }
    }

    function delete(int $id, string $referToColumn): bool
    {
        return $_ENV["mysqli"]->rawQuery(
            $this->deleteQuery($id, $referToColumn)
        );
    }

    function deleteQuery(int $id, string $referToColumn): string
    {
        return "DELETE FROM `$this->table` WHERE `$referToColumn` = '$id'";
    }

    function updateQuery(int $id, array $data, string $referToColumn): string
    {
        $columns = [];
        foreach ($data as $key => $value) {
            if($value instanceof SqlQueryCastable) {
                $columns[] = "`$key`='{$value->toSqlString()}'";
            }
            else {
                $columns[] = "`$key`='$value'";
            }
        }
        return "UPDATE `$this->table` SET ".implode(', ',$columns)." WHERE `$referToColumn`='$id';";
    }

    function update(int $id, array $data, string $referToColumn): bool
    {
        return $_ENV["mysqli"]->rawQuery(
            $this->updateQuery($id, $data, $referToColumn)
        );
    }

    function createQuery(array $data): string
    {
        $keys = [];
        $values = [];
        foreach ($data as $key => $value) {
            $keys[] = "`$key`";
            if($value instanceof SqlQueryCastable) {
                $values[]= "'{$value->toSqlString()}'";
            }
            else {
                $values[] = "'$value'";
            }
        }
        $keys = implode(', ', $keys);
        $values = implode(', ', $values);
        return "INSERT INTO `$this->table`($keys) VALUES ($values);";
    }

    function create(array $data): bool
    {
        return $_ENV["mysqli"]->rawQuery(
            $this->createQuery($data)
        );
    }

    private function parseSelectQuery(?int $maxLength = null, ?int $startRow = null): string
    {
        $columns = "*";
        $where = "";
        $groups = "";
        $orders = "";
        $limit = "";
        if(!empty($this->columns)) {
            $columns = implode(', ', $this->columns);
        }
        if(!empty($this->where)) {
            $this->parseWhereGroups();
            $where = " WHERE ";
            for($i=0;$i<count($this->where);$i++) {
                if($i==0) {
                    $where .= $this->where[$i]["clause"];
                }
                else {
                    $where .= " ".$this->where[$i]["condition"]." ".$this->where[$i]["clause"];
                }
            }
        }
        if(!empty($this->groups)) {
            $groups = " GROUP BY ".implode(", ", $this->groups);
        }
        if(!empty($this->orders)) {
            $orders = " ORDER BY ".implode(", ", $this->orders);
        }
        if(!is_null($maxLength)) {
            if(!is_null($startRow)) {
                $limit = " LIMIT $startRow, $maxLength";
            }
            else {
                $limit = " LIMIT $maxLength";
            }
        }
        return "SELECT $columns FROM `$this->table`$where$groups$orders$limit;";
    }

    private function executeSelectQuery(string $query): array
    {
        $array = [];
        $query = $_ENV["mysqli"]->rawQuery($query);
        if($query->num_rows==0) {
            return [];
        }
        elseif($query->num_rows==1) {
            return $query->fetch_assoc();
        }
        else {
            for($i=0;$i<$query->num_rows;$i++) {
                $array []= $query->fetch_assoc();
            }
        }
        return $array;
    }
}