<?php declare(strict_types=1);

namespace Framework\Database;

class TableBuilder {
    public string $tableName = "";
    private array $columns = [];
    private array $indexes = [];
    private array $constraints = [];
    public int $indexCount = 0;
    public int $constraintsCount = 0;
    private bool $primary_exists = false;
    private static array $primaryKeys = [];
    private string $engine = "InnoDB";
    private string $charset = "utf8mb4";
    private string $collation = "utf8mb4_general_ci";

    function __construct(string $tableName) {
        $this->tableName = $tableName;
        return $this;
    }

    private function addIndex(string $keyType, string $columnName, string $indexName, string $using): TableBuilder
    {
        $this->indexes[] = [
            "key_type" => $keyType,
            "column_name" => $columnName,
            "index_name" => $indexName,
            "using" => $using,
        ];
        $this->indexCount++;
        return $this;
    }

    private function addConstraint(string $columnName, string $constraintName, string $foreignTable, ?string $foreignColumn): TableBuilder
    {
        $this->constraints[] = [
            "column_name" => $columnName,
            "constraint_name" => $constraintName,
            "foreign_table" => $foreignTable,
            "foreign_column" => $foreignColumn,
        ];
        $this->constraintsCount++;
        return $this;
    }

    private function addColumn(string $columnName, string $type, ?string $length, ?string $default, bool $nullable, array $extra): TableBuilder
    {
        $this->columns[] = [
            "name" => $columnName,
            "type" => $type,
            "length" => $length,
            "default" => $default,
            "nullable" => $nullable,
            "extra" => implode(" ", $extra),
        ];
        return $this;
    }

    function primary(string $columnName = "id"): TableBuilder
    {
        if(!$this->primary_exists) {
            $this->integer($columnName, null, false, ["PRIMARY KEY","AUTO_INCREMENT"]);
            $this->primary_exists = true;
            self::$primaryKeys[$this->tableName] = $columnName;
            $this->indexCount++;
        }
        return $this;
    }

    function boolean(string $columnName, ?bool $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        if(is_null($default)) {
            return $this->addColumn($columnName, "BOOLEAN",null, null, $nullable, $extra);
        }
        return $this->addColumn($columnName, "BOOLEAN",null, (string)(int)$default, $nullable, $extra);
    }

    function tinyInteger(string $columnName, ?int $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        if(is_null($default)) {
            return $this->addColumn($columnName, "TINYINT","4", null, $nullable, $extra);
        }
        return $this->addColumn($columnName, "TINYINT","4", (string)$default, $nullable, $extra);
    }

    function smallInteger(string $columnName, ?int $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        if(is_null($default)) {
            return $this->addColumn($columnName, "SMALLINT","8", null, $nullable, $extra);
        }
        return $this->addColumn($columnName, "SMALLINT","8", (string)$default, $nullable, $extra);
    }

    function mediumInteger(string $columnName, ?int $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        if(is_null($default)) {
            return $this->addColumn($columnName, "MEDIUMINT","10", null, $nullable, $extra);
        }
        return $this->addColumn($columnName, "MEDIUMINT","10", (string)$default, $nullable, $extra);
    }

    function integer(string $columnName, ?int $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        if(is_null($default)) {
            return $this->addColumn($columnName, "INT", "11", null, $nullable, $extra);
        }
        return $this->addColumn($columnName, "INT", "11", (string)$default, $nullable, $extra);
    }

    function bigInteger(string $columnName, ?int $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        if(is_null($default)) {
            return $this->addColumn($columnName, "BIGINT","20", null, $nullable, $extra);
        }
        return $this->addColumn($columnName, "BIGINT","20", (string)$default, $nullable, $extra);
    }

    function float(string $columnName, ?float $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        if(is_null($default)) {
            return $this->addColumn($columnName, "FLOAT", null, null, $nullable, $extra);
        }
        return $this->addColumn($columnName, "FLOAT", null, (string)$default, $nullable, $extra);
    }

    function decimal(string $columnName, int $size = 10, int $decimal = 0, ?float $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        if(is_null($default)) {
            return $this->addColumn($columnName, "DECIMAL",$size.",".$decimal, null, $nullable, $extra);
        }
        return $this->addColumn($columnName, "DECIMAL",$size.",".$decimal, (string)$default, $nullable, $extra);
    }

    function string(string $columnName, int $length = 20, ?string $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        return $this->addColumn($columnName, "VARCHAR", (string)$length, $default, $nullable, $extra);
    }

    function text(string $columnName, int $length = 200, ?string $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        return $this->addColumn($columnName, "TEXT", (string)$length, $default, $nullable, $extra);
    }

    function date(string $columnName, ?string $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        return $this->addColumn($columnName, "DATE", null, $default, $nullable, $extra);
    }
    
    function time(string $columnName, ?int $precision = 0, ?string $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        return $this->addColumn($columnName, "TIME", (string)$precision, $default, $nullable, $extra);
    }

    function datetime(string $columnName, ?int $precision = 0, ?string $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        return $this->addColumn($columnName, "DATETIME", (string)$precision, $default, $nullable, $extra);
    }

    function timestamp(string $columnName, ?int $precision = 0, ?string $default = null, bool $nullable = false, array $extra = []): TableBuilder
    {
        return $this->addColumn($columnName, "TIMESTAMP", (string)$precision, $default, $nullable, $extra);
    }

    function foreign(string $columnName, string $foreignTable, ?string $foreignColumn = null, ?int $default = null, bool $nullable = false): TableBuilder
    {
        $this->integer($columnName, $default, $nullable);
        $this->addIndex("KEY", $columnName, $columnName, "BTREE");
        $this->addConstraint($columnName, $columnName, $foreignTable, $foreignColumn);
        return $this;
    }

    function prepareColumnQuery(): string
    {
        $columns = [];
        foreach($this->columns as $column) {
            if($column['nullable']) {
                $nullable = 'NULL';
            }
            else {
                $nullable = "NOT NULL";
            }
            if(!is_null($column["default"])) {
                $default = "DEFAULT '{$column["default"]}'";
            }
            else {
                $default = "";
            }
            if(!is_null($column['length'])) {
                $length = "({$column['length']})";
            }
            else {
                $length = "";
            }
            $columns[] = sprintf(
                "`%s` %s%s %s %s %s",
                $column['name'],
                $column['type'],
                $length,
                $nullable,
                $default,
                $column["extra"],
            );
        }
        
        $query = sprintf(
            'CREATE TABLE `%s` (%s) ENGINE=%s DEFAULT CHARSET=%s COLLATE=%s;',
            $this->tableName,
            implode(',',$columns),
            $this->engine,
            $this->charset,
            $this->collation,
            $this->tableName,
        );

        return $query;
    }

    function prepareIndexQuery(): string
    {
        $indexes = [];
        $query = "";
        foreach($this->indexes as $index) {
            if($index['key_type']!="PRIMARY KEY") {
                $index['using'] = "USING ".$index['using'];
            }
            $indexes[] = sprintf(
                'ADD %s `%s` (`%s`) %s',
                $index['key_type'],
                $index['column_name'],
                $index['index_name'],
                $index['using']
            );
        }
        if(!empty($indexes)) {
            $query = sprintf(
                'ALTER TABLE `%s` %s;',
                $this->tableName,
                implode(',',$indexes)
            );
        }
        return $query;
    }

    function prepareConstraintsQuery(): string
    {
        $constraints = [];
        $query = "";
        foreach($this->constraints as $constraint) {
            if(is_null($constraint['foreign_column'])) {
                $foreignColumn = self::$primaryKeys[$constraint['foreign_table']];
            }
            else {
                $foreignColumn = $constraint['foreign_column'];
            }
            $constraints[] = sprintf('ADD CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s` (`%s`)',
            $this->tableName."_".$constraint['constraint_name'],
            $constraint['column_name'],
            $constraint['foreign_table'],
            $foreignColumn
        );
        }
        if(!empty($constraints)) {
            $query = sprintf(
                'ALTER TABLE `%s` %s;',
                $this->tableName,
                implode(',',$constraints)
            );
        }
        return $query;
    }

    /*function __toString()
    {
        return $this->prepareColumnQuery()."<br/>".$this->prepareIndexQuery();
    }*/
}