<?php

namespace Bitmap\Query;

use Bitmap\Entity;
use Bitmap\Query\Context\Context;

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

    public function sql()
    {
        $values = $this->fieldValues();

        return sprintf(
            "insert into `%s` (%s) values (%s)",
            $this->mapper->getTable(),
            implode(", ", array_map(function ($value) { return "`$value`"; }, array_keys($values))),
            implode(", ", array_values($values))
        );
    }
}