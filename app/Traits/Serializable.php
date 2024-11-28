<?php

namespace Mike\Bnovo\Traits;

trait Serializable
{
    public function toArray(): array
    {
        $reflect = new \ReflectionClass($this);
        $result = [];

        foreach ($reflect->getProperties() as $prop) {
            if ($prop->isStatic() || $prop->isPrivate() || $prop->isProtected()) {
                continue;
            }

            if (!$prop->isInitialized($this)) {
                continue;
            }

            $fieldName = self::toSnakeCase($prop->getName());
            $result[$fieldName] = $prop->getValue($this);
        }

        return $result;
    }

    public static function fromArray(array $array): static
    {
        $keys = array_keys($array);

        for ($i = 0; $i < count($keys); $i++) {
            if (mb_strtoupper($keys[$i], 'utf-8') == $keys[$i]) {
                continue;
            }

            $keys[$i] = static::toCamelCase($keys[$i]);
        }

        $array = array_combine($keys, $array);
        $reflect = new \ReflectionClass(static::class);
        $constructorParams = $reflect->getConstructor()?->getParameters();
        $insertedParams = [];

        if ($constructorParams) {
            foreach ($constructorParams as $param) {
                if (array_key_exists($param->getName(), $array)) {
                    $insertedParams[$param->getName()] = $array[$param->getName()];
                    unset($array[$param->getName()]);
                }
            }
        }

        $instance = new static(...$insertedParams);

        foreach ($array as $name => $value) {
            $instance->{$name} = $value;
        }

        return $instance;
    }

    protected static function toSnakeCase(string $string): string
    {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
    }

    protected static function toCamelCase(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }
}