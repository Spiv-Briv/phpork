<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Terminal\Terminal;

class ModelBuilder
{
    private const TODO_DOCS = "\n* // TODO: write properties of this model";
    private const TODO_COLUMNS = "// TODO: put columns to get";
    private const TODO_CAST = "// TODO: Put your casts here";
    private const LINKED_PROPERY = "protected static string \$linkedProperty = \"";
    private const STRING_TREE = "protected static ?array \$stringTree = [";
    private ?string $model;
    private ?string $table;
    private ?array $columns;
    private ?array $casts;
    private ?string $linkedProperty;
    private ?array $stringTree;
    private string $filename;
    private bool $interactive;
    private bool $force;
    private bool $documentation;

    function __construct(?string $model, ?string $table, ?string $columns, ?string $casts, ?string $linkedProperty, ?string $stringTree, array $flags)
    {
        $this->interactive = in_array('interactive', $flags);
        $this->force = in_array('force', $flags);
        $this->documentation = in_array('documentation', $flags);
        if(!$this->interactive&&is_null($model)) {
            echo Terminal::error("Model name not provided");
            return;
        }
        if ($model == "Model") {
            echo Terminal::error("Model is reserved name.");
            return;
        }
        if (!$this->interactive&&$this->modelExist($model) && !$this->force) {
            echo Terminal::error("Model already exist.").Terminal::warning("Add ".Terminal::variable("--force", Terminal::WARNING)." flag to overwrite it");
            return;
        }
        $this->model = $model;
        $this->filename = RELATIVE_PATH . "App/Models/$model.php";
        $this->table = $this->parseTableName($table);
        if (!is_null($columns)) {
            $this->columns = explode(',', $columns);
        }
        else {
            $this->columns = null;
        }
        $this->casts = $this->parseCasts($casts);
        $this->linkedProperty = $linkedProperty;
        $this->stringTree = $this->parseStringTree($stringTree);
        if ($this->interactive) {
            $this->interactiveBuild();
        }
        $this->compileFile();
        echo Terminal::success("Model created");
    }

    private function createDocumentation(): string
    {
        $documentation = "";
        if(is_null($this->columns)) {
            return self::TODO_DOCS;
        }
        foreach($this->columns as $column) {
            $type = "string";
            if(!is_null($this->casts)&&array_key_exists($column, $this->casts)) {
                $type = trim(rtrim($this->casts[$column], "::class"), "\"");
                if($type=='date'||$type=='time'||$type=='datetime') {
                    $type = "DateTime";
                }
            }
            $documentation .= "\n* @property $type $column";
        }
        return $documentation;
    }

    private function parseTableName(?string $table): ?string 
    {
        if(is_null($table)) {
            if(is_null($this->model)) {
                return null;
            }
            echo Terminal::warning("Table name not provided. Using model name");
            return $this->model;
        }
        return $table;
    }

    private function parseCasts(?string $casts): ?array
    {
        if(is_null($casts)) {
            return null;
        }
        $subcasts = [];
        foreach (explode(',', $casts) as $subcast) {
            $subcast = explode('>', $subcast);
            if (count($subcast) != 2) {
                echo Terminal::error("Invalid casts format");
                continue;
            }
            if (!in_array($subcast[0], $this->columns)) {
                echo Terminal::warning("{$subcast[0]} is undefined. Skipping...");
                continue;
            }
            if (!class_exists("App\\Models\\" . $subcast[1])) {
                $subcasts[$subcast[0]] = "\"{$subcast[1]}\"";
            } else {
                $subcasts[$subcast[0]] = "{$subcast[1]}::class";
            }
        }
        return $subcasts;
    }

    private function parseStringTree(?string $stringTree): ?array
    {
        if(is_null($stringTree)) {
            return null;
        }
        $columns = [];
        foreach (explode(',', $stringTree) as $column) {
            if (empty($column)) {
                echo Terminal::warning("Invalid formatting. Skipping");
                continue;
            }
            if (!str_contains($column, ".") && !is_null($this->columns)) {
                if(str_contains($column, '>')) {
                    $noAliasColumn = explode('>', $column)[0];
                }
                else {
                    $noAliasColumn = $column;
                }
                if(!in_array($noAliasColumn, $this->columns)) {
                    echo Terminal::warning("{$column} is undefined. Skipping...");
                    continue;
                }
            }
            if (str_contains($column, ">")) {
                $alias = explode('>', $column);
                $columns[$alias[1]] = $alias[0];
            } else {
                $columns[] = $column;
            }
        }
        return $columns;
    }

    private function modelExist(string $model): bool
    {
        return file_exists("App\\Models\\$model.php");
    }

    private function compileFile(): void
    {
        $model = $this->model;
        if (is_null($this->table)) {
            $table = mb_strtolower($model);
        } else {
            $table = $this->table;
        }
        if(is_null($this->columns)) {
            $columns = "\n\t\t".self::TODO_COLUMNS."\n\t";
        }
        else {
            $columns = "\n\t\t\"" . implode("\",\n\t\t\"", $this->columns) . "\",\n\t";
        }
        if (is_null($this->casts)) {
            $casts = self::TODO_CAST;
        } else {
            $casts = "";
            foreach ($this->casts as $key => $item) {
                $casts .= "\"$key\" => $item,\n\t\t";
            }
            $casts = rtrim($casts);
        }
        if (is_null($this->linkedProperty)) {
            $linkedProperty = "";
        } else {
            $linkedProperty = "\n\t" . self::LINKED_PROPERY . "{$this->linkedProperty}\";";
        }
        if (is_null($this->stringTree)) {
            $stringTree = "";
        } else {
            $stringTree = "\n\t" . self::STRING_TREE;
            foreach ($this->stringTree as $key => $item) {
                if (!is_numeric($key)) {
                    $stringTree .= "\n\t\t\"$key\" => \"$item\",";
                } else {
                    $stringTree .= "\n\t\t\"$item\",";
                }
            }
            $stringTree .= "\n\t];";
        }
        if(!$this->documentation) {
            $documentation = self::TODO_DOCS;
        }
        else {
            $documentation = $this->createDocumentation();
        }
        $pattern = file_get_contents("Framework/Terminal/Patterns/Model.txt");
        file_put_contents($this->filename, sprintf($pattern, "<?php", $documentation, $model, $table, $columns, $casts, $linkedProperty, $stringTree));
    }

    private function interactiveBuild(): void
    {
        while (is_null($this->model)) {
            $model = Terminal::prompt("Type in model name: ");
            if (!empty($model)) {
                if ($model == "Model") {
                    Terminal::error("It is reserved name.");
                    continue;
                }
                if ($this->modelExist($model) && !$this->force) {
                    Terminal::warning("Model already exist. Try again");
                    continue;
                }
                $this->model = $model;
                $this->filename = RELATIVE_PATH . "App/Models/$model.php";
            }
        }
        while (is_null($this->table)) {
            $table = Terminal::prompt("Type in table associated with model: ");
            if (!empty($table)) {
                $this->table = $table;
            }
        }
        while (is_null($this->columns)) {
            $columns = Terminal::prompt("Type in columns to get from table: ");
            if (!empty($columns)) {
                $this->columns = explode(',', $columns);
            }
        }
        if (is_null($this->casts)) {
            $casts = Terminal::prompt("Type in model casts (pattern:column>type,column>type): ");
            if (!empty($casts)) {
                $this->casts = $this->parseCasts($casts);
            } else {
                Terminal::warning("Skipped");
            }
        }
        if (is_null($this->linkedProperty)) {
            $linkedProperty = Terminal::prompt("Type in property that is used to search in database: ");
            if (!empty($linkedProperty)) {
                if (!in_array($linkedProperty, $this->columns)) {
                    echo Terminal::warning("$linkedProperty is undefined. Skipping...");
                } else {
                    $this->linkedProperty = $linkedProperty;
                }
            } else {
                Terminal::warning("Skipped");
            }
        }
        if (is_null($this->stringTree)) {
            $stringTree = Terminal::prompt("Type in properties to display when outputing data: ");
            if (!empty($stringTree)) {
                $this->stringTree = $this->parseStringTree($stringTree);
            } else {
                Terminal::warning("Skipped");
            }
        }
    }
}
