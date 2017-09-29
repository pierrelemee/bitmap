<?php

namespace Bitmap\Query;

use Bitmap\Entity;
use Bitmap\Query\Context\Context;
use PDO;

class Insert extends ModifyEntityQuery
{
    /**
     * @var Context
     */
    protected $context;

    public function __construct(Entity $entity, $context)
    {
        parent::__construct($entity, $context);
    }

    /**
     * @{@inheritdoc}
     * @param string $class
     *
     * @return self
     */
    public static function fromEntity(Entity $entity, $with = [])
    {
        return new Insert($entity, $with);
    }

    public function sql(PDO $connection)
    {
        $values = $this->fieldValues();

        $names = [];

        foreach (array_keys($values) as $name) {
            $names[] = $this->escapeName($name, $connection);
        }

        return sprintf(
            "insert into %s (%s) values (%s)",
            $this->escapeName($this->mapper->getTable(), $connection),
            implode(", ", $names),
            implode(", ", array_values($values))
        );
    }
}