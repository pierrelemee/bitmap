<?php

namespace PierreLemee\Bitmap;

use PDO;
use PierreLemee\Bitmap\Mappers\AnnotationMapper;

abstract class Entity
{
    protected $bitmapHash;

    protected function getBitmapHash()
    {
        return $this->bitmapHash;
    }

    /**
     * @return Entity
     */
    protected function computeHash()
    {
        $this->bitmapHash = $this->getMapper()->hash($this);
        return $this;
    }

    /**
     * @return Mapper
     */
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
                $entities[] = Bitmap::getMapper($class)->load($data)->computeHash();
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
            return Bitmap::getMapper($class)->load($data)->computeHash();
        }
        return null;
    }

    /**
     *
     * @param $connection string the name of the connection to use
     *
     * @return boolean whether the save operation completed successfully
     */
    public function save($connection = null)
    {
        if ($this->bitmapHash === null) {
            $status = $this->getMapper()->insert($this, $connection);
        } else {
            $status = $this->getMapper()->update($this, $connection);
        }

        return $status;
    }

    /**
     * Deletes the entity in the database
     *
     * @param $connection string the name of the connection to use
     *
     * @return boolean whether the deletion was completed successfully
     */
    public function delete($connection = null)
    {
        return $this->getMapper()->delete($this, $connection);
    }
}