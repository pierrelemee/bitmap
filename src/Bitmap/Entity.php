<?php

namespace Bitmap;

use Bitmap\Query\Select;
use PDO;
use Bitmap\Mappers\AnnotationMapper;

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
    public function setBitmapHash($hash)
    {
        $this->bitmapHash = $hash;
    }

    /**
     * @return Mapper
     */
    protected function getMapper()
    {
        return AnnotationMapper::of($this);
    }

    public static function mapper($class)
    {
        if (!Bitmap::hasMapper($class)) {
            Bitmap::addMapper((new $class())->getMapper());
        }

        return Bitmap::getMapper($class);
    }

    public static function select()
    {
        return new Select(self::mapper(get_called_class()));
    }

    /**
     * @param string $sql
     * @param string $connection
     *
     * @return Entity[]
     */
    public static function find($sql, $connection = null)
    {
        $mapper = self::mapper(get_called_class());
        $stmt = Bitmap::connection($connection)->query($sql, PDO::FETCH_ASSOC);

        $entities = [];

        if (false !== $stmt) {
            while (false !== ($data = $stmt->fetch())) {
                $entities[] = $mapper->load($data);
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
            return Bitmap::getMapper($class)->load($data);
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