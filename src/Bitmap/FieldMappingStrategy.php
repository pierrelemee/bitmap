<?php

namespace Bitmap;

/**
 * Class FieldMappingStrategy
 *
 * Responsible of mapping a result set array
 * @package Bitmap
 *
 */
abstract class FieldMappingStrategy
{
    const STRATEGY_PREFIX = 0;
    const STRATEGY_GROUP = 1;

    /**
     * @var Mapper $mapper
     */
    protected $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public abstract function getPdoFetchingType();

    public function map(array $result, $mapping = null)
    {
        return $this->mapValues($result, is_array($mapping) ? $mapping : $this->mapping());
    }

    /**
     * @param $result array
     * @param $mapping array
     * @return array
     */
    protected abstract function mapValues(array $result, array $mapping);

    protected abstract function mapping();

    public static function of(Mapper $mapper)
    {
        $class = get_called_class();
        return new $class($mapper);
    }
}