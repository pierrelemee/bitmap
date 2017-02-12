<?php

namespace Bitmap\Strategies;

use Bitmap\FieldMappingStrategy;
use Bitmap\Mapper;
use PDO;

class GroupStrategy extends FieldMappingStrategy
{
    public function __construct(Mapper $mapper)
    {
        parent::__construct($mapper);
    }

    public function getPdoFetchingType()
    {
        return PDO::FETCH_NUM;
    }

    public function mapValues(array $result, array $mapping)
    {
        $values = [];

        for ($i = 0; $i < sizeof($result); $i++) {
            foreach ($mapping as $table => $columns) {
                foreach ($columns as $name => $index) {
                    if ($index === $i) {
                        $values[$table][$name] = $result[$i];
                        break;
                    }
                }
            }
        }

        return $values;
    }

    /**
     * Returns something like
     * [
     *      "Artist" =>
     *          [
     *              "ArtistId" => 0
     *              "Name" => 1
     *          ]
     *      ,
     *      "Album" =>
     *          [
     *              ...
     *          ]
     *      ...
     * ]
     *
     * @return array
     */
    public function mapping()
    {
        $index = 0;
        $mapping = [];

        foreach ($this->mapper->getFields() as $field) {
            $mapping[$this->mapper->getTable()][$field->getColumn()] = $index++;
        }

        foreach ($this->mapper->associations() as $association) {
            $mapping[$association->getMapper()->getTable()] = [];
            foreach ($association->getMapper()->getFields() as $field) {
                $mapping[$association->getMapper()->getTable()][$field->getColumn()] = $index++;
            }
        }

        return $mapping;
    }
}