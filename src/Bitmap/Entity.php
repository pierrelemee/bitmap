<?php

namespace PierreLemee\Bitmap;

use PDO;
use PierreLemee\Bitmap\Mappers\AnnotationMapper;

abstract class Entity
{
    protected function getMapper()
    {
        return AnnotationMapper::of($this);
    }

    /**
     * @param string $sql
     * @param string $connection
     *
     * @return Entity[]
     */
    public static function find($sql, $connection = null)
    {
        $class = get_called_class();
        if (!Bitmap::hasMapper(get_called_class())) {
            Bitmap::addMapper((new $class())->getMapper());
        }
        $stmt = Bitmap::connection($connection)->query($sql, PDO::FETCH_ASSOC);

        $entities = [];

        if (false !== $stmt) {
            while (false !== ($data = $stmt->fetch())) {
                $entity = new $class();
                foreach ($data as $key => $value) {
                    if (Bitmap::getMapper(get_called_class())->hasField($key)) {
                        Bitmap::getMapper(get_called_class())->getField($key)->set($entity, $value);
                    }
                }
                $entities[] = $entity;
            }
        }
        return $entities;
    }

    /**
     * @param string $sql
     * @param string $connection
     *
     * @return Entity
     */
    public static function findOne($sql, $connection = null)
    {
        $class = get_called_class();
        if (!Bitmap::hasMapper(get_called_class())) {
            Bitmap::addMapper((new $class())->getMapper());
        }
        $stmt = Bitmap::connection($connection)->query($sql, PDO::FETCH_ASSOC);

        if (false !== $stmt && false !== ($data = $stmt->fetch())) {
            $entity = new $class();
            foreach ($data as $key => $value) {
                if (Bitmap::getMapper(get_called_class())->hasField($key)) {
                    Bitmap::getMapper(get_called_class())->getField($key)->set($entity, $value);
                }
            }
            return $entity;
        }
        return null;
    }
}