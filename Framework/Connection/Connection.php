<?php declare(strict_types=1);

namespace Framework\Connection;

use mysqli;
use mysqli_result;
use mysqli_sql_exception;

class Connection
{
    protected mysqli|false $base;
    protected ?string $database = null;
    private ?string $table = null;

    public function __construct(string $host, string $username, string $password, string $database)
    {
        $this->base = mysqli_connect($host, $username, $password);
        if (!empty($database)) {
            $this->setDatabase($database);
        }
    }

    public function getDatabases(): ?array
    {
        $result = $this->base->query("SHOW DATABASES;");
        $return = [];
        foreach($result as $item) {
                $return[]= $item["Database"];
        }
        return $return;
    }

    public function getTables(): ?array
    {
        if (!$this->isDatabaseSet()) {
            return null;
        }
        $result = $this->base->query("SHOW TABLES;");
        $return = [];
        foreach($result as $item) {
            $return[] = $item["Tables_in_{$this->getDatabase()}"];
        }
        return $return;
    }

    public function getColumns(): ?array
    {
        if (!$this->isTableSet()) {
            return null;
        }
        $result = $this->base->query("DESCRIBE `{$this->getDatabase()}`.`{$this->getTable()}`");
        $return = [];
        foreach($result as $item) {
            $subreturn = [];
            foreach($item as $subkey => $subitem) {
                $subreturn[$subkey] = $subitem;
            }
            $return[] = $subreturn;
        }
        return $return;
    }

    public function getColumnsName(): ?array
    {
        if (!$this->isTableSet()) {
            return null;
        }
        $result = $this->base->query("DESCRIBE `{$this->getDatabase()}`.`{$this->getTable()}`");
        $return = [];
        foreach($result as $item) {
            $return[] = $item["Field"];
        }
        return $return;
    }

    public function rawQuery(string $query): mysqli_result|bool
    {
        return $this->base->query($query);
    }

    /** Returns true if database was changed succesfully and false if a problem occured */
    public function setDatabase(string $database): bool
    {
        try {
            $this->base->select_db($database);
            $this->database = $database;
            return true;
        }
        catch(mysqli_sql_exception) {
            $this->database = null;
            return false;
        }
        
    }

    public function getDatabase(): ?string {
        return $this->database;
    }

    public function setTable(?string $table): bool
    {
        if(is_null($table)) {
            $this->table = $table;
            return true;
        }
        try {
            if(!in_array($table,$this->getTables())) {
                throw new mysqli_sql_exception("Table $table doesn't exists");
            }
            $this->table = $table;
            return true;
        }
        catch(mysqli_sql_exception) {
            $this->table = null;
            return false;
        }
    }

    public function getTable(): ?string
    {
        return $this->table;
    }

    public function isTableSet(): bool
    {
        return $this->table != null;
    }

    public function isDatabaseSet(): bool
    {
        return $this->database != null;
    }

    public function mapDatabase(): string
    {
        $activeTable = $this->getTable();
        $string = "<ul>";
        $tables = $this->getTables();
        foreach($tables as $table) {
            echo $table.", ";
            $this->setTable($table);
            $columns = $this->getColumnsName();
            $string .= "<li>{$this->getTable()}<ul>";
            foreach($columns as $column) {
                $string .= "<li>$column</li>";
            }
            $string .= "</ul></li>";
        }
        $string .= "</ul>";
        $this->setTable($activeTable);
        return $string;
    }

    public function mapTable(): string
    {
        $string = "<ul>";
        $string .= "<li>{$this->getTable()}<ul>";
            foreach($this->getColumnsName() as $column) {
                $string .= "<li>$column</li>";
            }
        $string .= "</ul></li></ul>";
        return $string;
    }

    public function __toString()
    {
        return $this->getDatabase()."->".$this->getTable();
    }
}