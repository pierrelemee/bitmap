<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Bitmap\Mapper;
use Bitmap\Query\Context\Context;
use Exception;
use PDO;

class Update extends Query
{
    protected $entity;
    /**
     * @var Context
     */
    protected $context;

    public function __construct(Mapper $mapper, Entity $entity, Context $context)
    {
        parent::__construct($mapper);
        $this->entity = $entity;
        $this->context = $context;
    }

    public function execute(PDO $connection)
    {
        $values = [];

        foreach ($this->mapper->getFields() as $name => $field) {
            if ($field !== $this->mapper->getPrimary()) {
                $key = self::escapeName($field->getColumn(), $connection);
                if (!isset($values[$key])) {
                    if (null !== $value = $field->getValue($this->entity)) {
                        $values[$key] = $value;
                    } else {
                        if (!$field->isIncremented() && !$field->hasDefault()) {
                            $values[$key] = null;
                        }
                    }
                }
            }
        }

        foreach ($this->mapper->associations() as $name => $association) {
            if ($association->hasLocalValue() && ($association->getMapper()->hasPrimary() || $this->context->hasDependency($association->getName()))) {
                $entities = is_array($association->get($this->entity)) ? $association->get($this->entity) : [$association->get($this->entity)];
                foreach ($entities as $entity) {
                    if (null !== $entity && null !== $value = $association->getMapper()->getPrimary()->get($entity)) {
                        $values[$this->escapeName($association->getColumn(), $connection)] = $value;
                    }
                }
            }
        }

        $sql = sprintf(
            "update %s set %s where %s = ?",
            self::escapeName($this->mapper->getTable(), $connection),
            implode(", ", array_map(function ($column) { return "$column = ?";}, array_keys($values))),
            self::escapeName($this->mapper->getPrimary()->getColumn(), $connection)
        );

        Bitmap::current()->getLogger()->info("Running query",
            [
                'mapper' => $this->mapper->getClass(),
                'sql'    => $sql,
                'values' => array_values($values)
            ]
        );

        $statement = $connection->prepare($sql);

        if (!$statement->execute(array_merge(array_values($values), [$this->mapper->getPrimary()->getValue($this->entity)]))) {
            throw new Exception(sprintf("[%s]", implode(", ", array_values($statement->errorInfo())),  $statement->errorCode()));
        }

        return $statement->rowCount() === 1;
    }
}