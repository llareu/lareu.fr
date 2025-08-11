<?php

namespace Core\Database;

class Hydrator
{

    public static function hydrate(array $array, string $object)
    {
        $înstance = new $object();
        foreach ($array as $key => $value) {
            $method = self::getSetter($key);

            if (method_exists($înstance, $method)) {
                $înstance->$method($value);
            } else {
                $property = lcfirst(self::getProperty($key));
                $înstance->$property = $value;
            }
        }
        return $înstance;
    }


    private static function getSetter(string $columnName): string
    {
        return 'set' . self::getProperty($columnName);
    }

    private static function getProperty(string $columnName): string
    {
        return join('', array_map('ucfirst', explode('_', $columnName)));
    }
}
