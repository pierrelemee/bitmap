<?php

namespace Bitmap;

use Bitmap\Query\Context\Context;
use Bitmap\Query\Select;
use Exception;

abstract class Entity
{
    protected $bitmapHash;
	/**
	 * @var Mapper
	 */
    protected $mapper;

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
    protected function mapper()
    {
    	if (null === $this->mapper) {
    		$this->mapper = $this->getMapper();
	    }

    	return $this->mapper;
    }

    /**
     * @return Mapper
     */
    public abstract function getMapper();

    public static function getClassMapper($class)
    {
        if (!Bitmap::hasMapper($class)) {
        	$entity = new $class();
            Bitmap::addMapper($entity->mapper());
        }

        return Bitmap::getMapper($class);
    }

    public static function select()
    {
        return new Select(self::getClassMapper(get_called_class()));
    }

    /**
     * @param $connection string the name of the connection to use
     * @param null|array|Context $context the list of association (by their names) to recursively save
     *
     * @return boolean whether the save operation completed successfully
     *
     * @throws Exception
     */
    public function save($context = null, $connection = null)
    {
        $context = $context ? Context::fromContext($context) : Context::fromMapper($this->mapper());
        if ($this->bitmapHash === null) {
            $status = $this->mapper()->insert($this, $context, $connection);
        } else {
            $status = $this->mapper()->update($this, $context, $connection);
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