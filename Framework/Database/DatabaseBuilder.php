<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Connection\QueryBuilder;
use Framework\Terminal\Terminal;
use Framework\Database\TableBuilder;
use mysqli_sql_exception;

class DatabaseBuilder
{

    static function tablesDefinition(): array
    {
        $tables = [];

        $buffer = new TableBuilder("navbar");
        $tables[] = $buffer->primary()
        ->integer('order')
        ->string('identifier')
        ->string('item', 30);

        $buffer = new TableBuilder("model_headings");
        $tables[] = $buffer->primary()
            ->integer('order')
            ->string('identifier')
            ->text('title')
            ->text('content');

        $buffer = new TableBuilder("collection_headings");
        $tables[] = $buffer->primary()
            ->integer('order')
            ->string('identifier')
            ->text('title')
            ->text('content');

        
        
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

            echo Terminal::success("\nCreated table `" . Terminal::variable('tables', Terminal::SUCCESS) . "`\n");
            $previousTable = $_ENV["mysqli"]->getTable();

            foreach ($tables as $table) {
                $_ENV["mysqli"]->rawQuery($table->prepareColumnQuery());
                $_ENV["mysqli"]->setTable($table->tableName);

                $tableManager = new QueryBuilder('tables', null);
                $tableManager->create([
                    "table_name" => $_ENV['mysqli']->getTable(),
                    "column_count" => count($_ENV["mysqli"]->getColumns())
                ]);
                echo Terminal::success("Created table `" . Terminal::variable($_ENV['mysqli']->getTable(), Terminal::SUCCESS) . "` with " . Terminal::variable(count($_ENV['mysqli']->getColumns()), Terminal::SUCCESS) . " columns");
            }
            foreach($tables as $key => $table) {
                $query = $table->prepareIndexQuery();
                if(!empty($query)) {
                    if($key==0) {
                        echo Terminal::printl("");
                    }
                    $_ENV["mysqli"]->rawQuery($query);
                    echo Terminal::success("Added ".Terminal::variable($table->indexCount,Terminal::SUCCESS)." indexes to `".Terminal::variable($table->tableName, Terminal::SUCCESS)."` table");
                }
            }
            foreach($tables as $key => $table) {
                $query = $table->prepareConstraintsQuery();
                if(!empty($query)) {
                    if($key==0) {
                        echo Terminal::printl("");
                    }
                    $_ENV["mysqli"]->rawQuery($query);
                    echo Terminal::success("Added ".Terminal::variable($table->constraintsCount,Terminal::SUCCESS)." constraints to `".Terminal::variable($table->tableName, Terminal::SUCCESS)."` table");
                }
            }
            $_ENV["mysqli"]->setTable($previousTable);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1007) {
                return Terminal::error($e->getMessage()) .
                    Terminal::warning("Add flag " . Terminal::variable("--recreate", Terminal::WARNING) . " to overwrite old database");
            }
            return Terminal::print($e->getCode(), Terminal::WHITE) . ": " . Terminal::error($e->getMessage());
        }
        return Terminal::success("\nCreated database `" . Terminal::variable($name, Terminal::SUCCESS) . "` with " . Terminal::variable(count($tables), Terminal::SUCCESS) . " tables\n");
    }

    static function delete(string $name): string
    {
        $_ENV["mysqli"]->rawQuery("DROP DATABASE IF EXISTS `$name`");
        return Terminal::error("Deleted database `" . Terminal::variable($name, Terminal::ERROR) . "`");
    }

    static function flush(string $name): string
    {
        $_ENV["mysqli"]->setDatabase($name);
        $tables = $_ENV["mysqli"]->getTables();
        foreach ($tables as $table) {
            $_ENV["mysqli"]->rawQuery("DROP TABLE `$table`");
        }
        return Terminal::error("Deleted " . Terminal::variable(count($tables), Terminal::ERROR) . " tables from database `" . Terminal::variable($name, Terminal::ERROR) . "`");
    }

    static function seed(string $name): string
    {
        $_ENV["mysqli"]->setDatabase($name);
        $files = array_diff(scandir(RELATIVE_PATH . 'Framework/Database/Seeders'), ['.', '..', '.gitignore']);
        $rows = 0;
        foreach ($files as $file) {

            $table = ltrim(explode('.', $file)[0], "0..9_");
            $_ENV["mysqli"]->setTable($table);

            $fileContent = file_get_contents(RELATIVE_PATH . 'Framework/Database/Seeders/' . $file);
            $fileContent = rtrim($fileContent);
            $fileContent = explode(PHP_EOL, $fileContent);

            $columns = array_shift($fileContent);
            $columns = str_replace('"', '`', $columns);
            foreach ($fileContent as $row) {
                $row = str_replace(',,', ',"",', $row);
                $_ENV["mysqli"]->rawQuery("INSERT INTO `$table`($columns) VALUES ($row)");
            }
            $rows += count($fileContent);
            echo Terminal::success("Seeded `" . Terminal::variable($table, Terminal::SUCCESS) . "` table with " . Terminal::variable(count($fileContent), Terminal::SUCCESS) . " rows");
        }
        return Terminal::success("\nSeeded " . Terminal::variable(count($files), Terminal::SUCCESS) . " tables with " . Terminal::variable($rows, Terminal::SUCCESS) . " rows");
    }

    private static function makeDatabase(string $name): void
    {
        $_ENV["mysqli"]->rawQuery("CREATE DATABASE `$name`");
        $_ENV["mysqli"]->setDatabase($name);
    }
}
