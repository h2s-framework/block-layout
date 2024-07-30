<?php

namespace Siarko\BlockLayout\Template;

use Siarko\BlockLayout\Exception\TemplateFileNotFound;
use Siarko\BlockLayout\Template\CallHandler\CallHandlerInterface;

class ContextualLoader
{
    /**
     * Dynamic data accessible with __set and __get
     * @var array
     */
    private array $__data = [];
    /**
     * Rendered template result
     * @var string|null
     */
    private ?string $__result = null;

    /**
     * @var string|null
     */
    private ?string $__path = null;

    private array $__callHandlers = [];


    /**
     * @param array $data
     * @param CallHandlerInterface[] $callHandlers
     */
    public function __construct(array $data = [], array $callHandlers = [])
    {
        $this->__setData($data);
        $this->__setCallHandlers($callHandlers);
    }

    /**
     * @param array $data
     */
    public function __setData(array $data = []): void{
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param string $path
     */
    public function setPath(string $path){
        $this->__path = $path;
    }

    /**
     * Render result in $this context
     * @return $this
     */
    public function render(): static
    {
        assert($this->__path != null);
        if(!file_exists($this->__path)){
            throw new TemplateFileNotFound($this->__path);
        }
        ob_start();
        include $this->__path;
        $this->__result = ob_get_clean();
        return $this;
    }

    public function __toString()
    {
        if($this->__result === null){
            $this->render();
        }
        return $this->__result;
    }

    public function get(string $name): mixed{
        return $this->__get($name);
    }

    public function set(string $name, mixed $value): static{
        $this->__set($name, $value);
        return $this;
    }

    public function __set($name, $value){
        $this->__data[$name] = $value;
    }

    public function __get($name){
        if(array_key_exists($name, $this->__data)){
            return $this->__data[$name];
        }
        return null;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed|null
     */
    public function __call($name, $arguments)
    {
        if($this->__callHandlerExists($name)){
            return $this->__callHandler($name, $arguments);
        }
        $type = substr($name, 0, 3);
        if($type == 'get'){
            $varname = lcfirst(substr($name, 3));
            return $this->__get($varname);
        }
        if($type == 'set'){
            $varname = lcfirst(substr($name, 3));
            $this->$varname = $arguments[0];
            return null;
        }
        throw new \BadMethodCallException("Method ".$name." not found in ".__CLASS__);
    }

    /**
     * @param string $name
     * @param callable $handler
     */
    public function registerCallHandler(string $name, callable $handler): void{
        $this->__callHandlers[$name] = $handler;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function __callHandlerExists(string $name): bool{
        return array_key_exists($name, $this->__callHandlers);
    }

    /**
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     */
    private function __callHandler(string $name, mixed $arguments): mixed
    {
        $method = $this->__callHandlers[$name];
        return $method(...$arguments);
    }

    /**
     * @param array $callHandlers
     * @return void
     */
    private function __setCallHandlers(array $callHandlers): void
    {
        foreach ($callHandlers as $name => $handler) {
            $this->registerCallHandler($name, function() use ($name, $handler){
                return $handler->handle($name, func_get_args());
            });
        }
    }
}