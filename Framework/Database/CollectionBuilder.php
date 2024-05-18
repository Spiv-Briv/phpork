<?php declare(strict_types=1);

namespace Framework\Database;

use App\Models\Model;
use Framework\Terminal\Terminal;

class CollectionBuilder
{
    private ?string $collection;
    private ?string $type;
    private ?int $pageSize;
    private string $filename;
    private bool $force;
    private bool $interactive;


    function __construct(?string $collection, ?string $type, ?string $pageSize, array $flags)
    {
        $this->interactive = in_array('interactive', $flags);
        $this->force = in_array('force', $flags);
        if(!$this->interactive&&is_null($collection)) {
            echo Terminal::error("Collection name not provided");
            return;
        }
        if($collection == "Collection") {
            echo Terminal::error("Collection is reserved name.");
            return;
        }
        if (!$this->interactive&&$this->collectionExists($this->getCollectionName($collection)) && !$this->force) {
            echo Terminal::error("Collection already exist.").Terminal::warning("Add ".Terminal::variable("--force", Terminal::WARNING)." flag to overwrite it");
            return;
        }
        $this->collection = $this->getCollectionName($collection);
        $this->filename = RELATIVE_PATH."App/Collections/{$this->collection}.php";
        $this->type = $this->getModel($type);
        if(!is_null($pageSize)&&!is_numeric($pageSize)) {
            echo Terminal::error('Page size is incorrect');
            return;
        }
        else {
            $this->pageSize = (int)$pageSize;
        }
        if($this->interactive) {
            $this->interactiveBuild();
        }
        $this->compileFile();
        echo terminal::success("Collection created");
    }

    private function getCollectionName(?string $name): ?string
    {
        if(is_null($name)) {
            return $name;
        }
        if(!str_ends_with($name, 'Collection')) {
            return $name."Collection";
        }
        return $name;
    }

    private function getModel(?string $type): string
    {
        if(class_exists("App\\Models\\$type")) {
            return "$type::class";
        }
        return "null";
    }

    private function collectionExists(string $collection): bool
    {
        return file_exists("App\\Collections\\$collection.php");
    }

    private function compileFile(): void
    {
        $collection = $this->collection;
        $type = $this->type;
        $pageSize = null;
        $use = "";
        if(!is_null($this->type)&&$this->type!="null") {
            $type = "\n\tprotected ?string \$type = {$this->type};";
            $use = "\nuse App\\Models\\".rtrim($this->type, "::class").";\n";
        }
        if($type=="null") {
            $type = null;
        }
        if($this->pageSize>0) {
            $pageSize = "\n\tprotected int \$elementsPerPage = {$this->pageSize};";
        }
        $pattern = file_get_contents(RELATIVE_PATH."Framework/Terminal/Patterns/Collection.txt");
        file_put_contents($this->filename, sprintf($pattern, "<?php", $use, $collection, $type, $pageSize));
    }

    private function interactiveBuild(): void
    {
        while(is_null($this->collection)) {
            $collection = Terminal::prompt("Type in collection name: ");
            if(!empty($collection)) {
                if($collection == "Collection") {
                    echo Terminal::error("Collection is reserved name.");
                    continue;
                }
                if (!$this->interactive&&$this->collectionExists($this->getCollectionName($collection)) && !$this->force) {
                    echo Terminal::error("Collection already exist.").Terminal::warning("Add ".Terminal::variable("--force", Terminal::WARNING)." flag to overwrite it");
                    continue;
                }
                $this->collection = $this->getCollectionName($collection);
                $this->filename = RELATIVE_PATH."App/Collections/{$this->collection}.php";
            }
        }
        if(is_null($this->type)||$this->type=="null") {
            $type = Terminal::prompt("Type in Model, that collection will enforce: ");
            if(!empty($type)) {
                $this->type = $this->getModel($type);;
            }
            else {
                echo Terminal::warning("Skipped");
            }
        }
        if($this->pageSize==0) {
            $pageSize = Terminal::prompt("Type in amount of elements in collection pagination: ");
            if(!empty($pageSize)&&is_numeric($pageSize)) {
                $this->pageSize = (int)$pageSize;
            }
            else {
                echo Terminal::warning("Empty or invalid page size. Skipped");
            }
        }
    }
}