<?php declare(strict_types=1);

namespace Framework\Connection;

use JsonSerializable;
use Stringable;

class ApiResponse implements Stringable, JsonSerializable
{
    private mixed $data;
    private array $request;
    private string $method;
    private string $desired_method;
    private bool $jsonParsable = true;

    function __construct(string $method, array $params, string $class, string $function)
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->desired_method = $method;
        $this->request = $_REQUEST;
        if($this->desired_method!=$this->method) {
            $this->jsonParsable = false;
            return $this->__toString();
        }
        if(count($params)!=count($this->request)) {
            $this->jsonParsable = false;
            return $this->__toString();
        }
        foreach($params as $param) {
            if(!array_key_exists($param, $this->request)) {
                $this->jsonParsable = false;
                return $this->__toString();
            }
        }
        $this->data = call_user_func_array("$class::$function", $this->request);
    }

    function jsonSerialize(): mixed
    {
        return [
            "method" => $this->method,
            "request" => $this->request,
            "data" => $this->data,
        ];
    }

    function __toString(): string
    {
        if(!$this->jsonParsable) {
            return "";
        }
        return json_encode($this);
    }
}