<?php

namespace Bitmap;

use Bitmap\Query\Context\Context;
use Bitmap\Query\Context\QueryContext;
use Bitmap\Query\Select;
use Exception;

abstract class Entity
{
    protected $bitmapHash;
	/**
	 * @var Mapper
	 */
    protected $mapper;

    private static function getBitmap()
    {
        return Bitmap::current();
    }

    public function getBitmapHash()
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
    public function getMapper()
    {
    	if (null === $this->mapper) {
    		$this->mapper = $this->createMapper();
	    }

    	return $this->mapper;
    }

    /**
     * @return Mapper
     */
    public function createMapper()
    {
        $mapper = new Mapper(get_class($this));
        $this->initializeMapper($mapper);
        return $mapper;
    }

    public abstract function initializeMapper(Mapper $mapper);

    public static function getClassMapper($class)
    {
        return static::getBitmap()->getMapper($class);
    }

    /**
     * @return Select
     */
    public static function select()
    {
        return new Select(self::getClassMapper(get_called_class()));
    }

    public function onPostLoad()
    {
        // To be implemented if needed
    }

    /**
     * @param $connection string the name of the connection to use
     * @param null|array|Context $with the list of association (by their names) to recursively save
     *
     * @return boolean whether the save operation completed successfully
     *
     * @throws Exception
     */
    public function save($with = null, $connection = null)
    {
        $context = ($with instanceof Context) ? $with : new QueryContext($this->getMapper(), $with);
        if ($this->bitmapHash === null) {
            $status = $this->getMapper()->insert($this, $context, $connection);
            $this->setBitmapHash(base64_encode(serialize($this->getMapper()->values($this))));
        } else {
            $status = $this->getMapper()->update($this, $context, $connection);
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