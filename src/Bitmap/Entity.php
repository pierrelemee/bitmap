<?php

namespace PierreLemee\Bitmap;

use PDO;

abstract class Entity
{
    protected abstract function getMapper();

    /**
     * @param string $sql
     *
     * @return Entity[]
     */
    public static function select($sql, $connection = null)
    {
        $class = get_called_class();
        if (!Bitmap::hasMapper(get_called_class())) {
            Bitmap::addMapper((new $class())->getMapper());
        }
        $stmt = Bitmap::connection($connection)->query($sql, PDO::FETCH_ASSOC);


        $entities = [];

        while (false !== ($data = $stmt->fetch())) {
            $entity = new $class();
            foreach ($data as $key => $value) {
                if (Bitmap::getMapper(get_called_class())->hasField($key)) {
                    Bitmap::getMapper(get_called_class())->getField($key)->set($entity, $value);
                }
            }
            $entities[] = $entity;
        }
        return $entities;
    }
}