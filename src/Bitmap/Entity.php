<?php

namespace PierreLemee\Bitmap;

use PDO;
use ReflectionMethod;

abstract class Entity
{
    protected function fields()
    {
        return array_keys(get_class_vars(get_class($this)));
    }

    /**
     * @param string $sql
     *
     * @return Entity[]
     */
    public static function select($sql, $connection = null)
    {
        $stmt = Bitmap::connection($connection)->query($sql, PDO::FETCH_ASSOC);
        $class = get_called_class();
        $entities = [];

        while (false !== ($data = $stmt->fetch())) {
            $entity = new $class();
            foreach ($data as $key => $value) {
                // Check public attribute
                if (property_exists($class, $key)) {
                    $entity->$key = $value;
                }
                if (method_exists($class, 'set' . ucfirst($key))) {
                    (new ReflectionMethod($class, 'set' . ucfirst($key)))->invoke($entity, $value);
                }
            }
            $entities[] = $entity;
        }
        return $entities;
    }
}