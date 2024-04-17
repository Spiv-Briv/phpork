<?php declare(strict_types=1);

namespace Framework\Database;

use Framework\Connection\QueryBuilder;
use Framework\Terminal\Terminal;
use Framework\Database\TableBuilder;
use mysqli_sql_exception;

class DatabaseBuilder {

    static function tablesDefinition(): array
    {
        $tables = [];

        $buffer = new TableBuilder("game");
        $tables[] = $buffer->primary()
        ->string('name',25)
        ->string('surname',35)
        ->date('birthdate')
        ->date('date')
        ->integer('team')
        ->integer('country');

        $buffer = new TableBuilder("countries");
        $tables[] = $buffer->primary()
        ->string('name',40)
        ->string('shortcut',4)
        ->string('colors',30);

        $buffer = new TableBuilder("teams");
        $tables[] = $buffer->primary()
        ->string("city",40)
        ->string('sponsor',40)
        ->string("trainer",70)
        ->integer("league")
        ->integer('country_id',1)
        ->integer('estabilished')
        ->string('stadium_name',45)
        ->integer('stadium_capacity')
        ->float('stadium_attendance')
        ->integer('ticket_price')
        ->integer('budget',0)
        ->string("colors",30);

        return $tables;
    }

    static function create(string $name): string
    {
        try {
            self::makeDatabase($name);
            $tables = self::tablesDefinition();
            $_ENV['mysqli']->rawQuery('CREATE TABLE `tables` (
                `table_name` VARCHAR(75) NOT NULL,
                `column_count` INT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;');
            
            echo Terminal::success("\nCreated table `".Terminal::variable('tables', Terminal::SUCCESS)."`\n");
            $previousTable = $_ENV["mysqli"]->getTable();

            foreach($tables as $table) {
                $_ENV["mysqli"]->rawQuery($table->prepareColumnQuery());
                $_ENV["mysqli"]->setTable($table->tableName);
                
                $tableManager = new QueryBuilder('tables');
                $tableManager->create([
                    "table_name" => $_ENV['mysqli']->getTable(),
                    "column_count" => count($_ENV["mysqli"]->getColumns())
                ]);
                echo Terminal::success("Created table `".Terminal::variable($_ENV['mysqli']->getTable(), Terminal::SUCCESS)."` with ".Terminal::variable(count($_ENV['mysqli']->getColumns()), Terminal::SUCCESS)." columns");
            }
            $_ENV["mysqli"]->setTable($previousTable); 
        }
        catch(mysqli_sql_exception $e) {
            if($e->getCode()==1007) {
                return Terminal::error($e->getMessage()).
                Terminal::warning("Add flag ".Terminal::variable("--recreate", Terminal::WARNING)." to overwrite old database");
            }
            return Terminal::print($e->getCode(), Terminal::WHITE).": ".Terminal::error($e->getMessage());
        }
        return Terminal::success("\nCreated database `".Terminal::variable($name, Terminal::SUCCESS)."` with ".Terminal::variable(count($tables), Terminal::SUCCESS)." tables\n");
    }

    static function delete(string $name): string
    {
        $_ENV["mysqli"]->rawQuery("DROP DATABASE IF EXISTS `$name`");
        return Terminal::error("Deleted database `".Terminal::variable($name, Terminal::ERROR)."`");
    }

    static function flush(string $name): string
    {
        $_ENV["mysqli"]->setDatabase($name);
        $tables = $_ENV["mysqli"]->getTables();
        foreach($tables as $table) {
            $_ENV["mysqli"]->rawQuery("DROP TABLE `$table`");
        }
        return Terminal::error("Deleted ".Terminal::variable(count($tables), Terminal::ERROR)." tables from database `".Terminal::variable($name, Terminal::ERROR)."`");
    }

    static function seed(string $name): string
    {
        $_ENV["mysqli"]->setDatabase($name);
        $files = array_diff(scandir(RELATIVE_PATH.'Framework/Database/Seeders'),['.','..']);
        $rows = 0;
        foreach($files as $file) {
            $table = explode('.',$file)[0];
            $_ENV["mysqli"]->setTable($table);

            $fileContent = file_get_contents(RELATIVE_PATH.'Framework/Database/Seeders/'.$file);
            $fileContent = rtrim($fileContent);
            $fileContent = explode(PHP_EOL,$fileContent);

            $columns = array_shift($fileContent);
            $columns = str_replace('"','`', $columns);
            foreach($fileContent as $row) {
                $row = str_replace(',,',',"",',$row);
                $_ENV["mysqli"]->rawQuery("INSERT INTO `$table`($columns) VALUES ($row)");
            }
            $rows += count($fileContent);
            echo Terminal::success("Seeded `".Terminal::variable($table,Terminal::SUCCESS)."` table with ".Terminal::variable(count($fileContent), Terminal::SUCCESS)." rows");
        }
        return Terminal::success("\nSeeded ".Terminal::variable(count($files), Terminal::SUCCESS)." tables with ".Terminal::variable($rows, Terminal::SUCCESS)." rows");
    }

    private static function makeDatabase(string $name): void
    {
        $_ENV["mysqli"]->rawQuery("CREATE DATABASE `$name`");
        $_ENV["mysqli"]->setDatabase($name);
    }
}