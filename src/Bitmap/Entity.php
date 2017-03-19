<?php

namespace Bitmap;

use Bitmap\Query\RawSelectQuery;
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
    public abstract function getMapper();

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

    public static function query($sql)
    {
        return new RawSelectQuery(self::mapper(get_called_class()), $sql);
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