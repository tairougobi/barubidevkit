<?php

namespace Core\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionParameter;

class Container implements ContainerInterface
{
    private array $bindings = [];
    private array $instances = [];

    public function set(string $id, $concrete = null): void
    {
        if (is_null($concrete)) {
            $concrete = $id;
        }
        $this->bindings[$id] = $concrete;
    }

    public function get(string $id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        $concrete = $this->bindings[$id] ?? $id;

        if ($concrete instanceof \Closure) {
            return $this->instances[$id] = $concrete($this);
        }

        return $this->instances[$id] = $this->build($concrete);
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || class_exists($id);
    }

    protected function build(string $concrete)
    {
        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$concrete} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();

        $instances = $this->resolveDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);
    }

    protected function resolveDependencies(array $dependencies): array
    {
        $results = [];
        foreach ($dependencies as $dependency) {
            $results[] = $this->resolve($dependency);
        }
        return $results;
    }

    protected function resolve(ReflectionParameter $parameter)
    {
        $type = $parameter->getType();

        if (is_null($type) || $type->isBuiltin()) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }
            throw new \Exception("Cannot resolve unresolvable dependency {$parameter->getName()}");
        }

        $className = $type->getName();

        return $this->get($className);
    }
}

