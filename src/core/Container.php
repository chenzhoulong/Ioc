<?php

namespace core;

use Closure;
use Exception;
use ReflectionClass;

class Container {

    private $registered = [];

    public function __set($k, $c)
    {
        $this->registered[$k] = $c;
    }

    public function __get($k) 
    {
        return $this->build($this->registered[$k]);
    }

    public function build($className) 
    {
        if ($className instanceof Closure) {
            return $className($this);
        }
        $reflector = new ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Can't instantiate this.");
        }
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return new $className;
        }
        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        return $reflector->newInstanceArgs($dependencies);
    }

    public function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if (is_null($dependency)) {
                $dependencies[] = $this->resolveNonClass($parameter);
            } else {
                $dependencies[] = $this->build($dependency->name);
            }
        }
        return $dependencies;
    }

    public function resolveNonClass($parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        throw new Exception('I have no idea what to do here.');
    }
    
}

