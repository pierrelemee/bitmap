<?php

namespace Bitmap\Query;

use Bitmap\Entity;

class Insert extends ModifyEntityQuery
{
    protected $with;

    public function __construct(Entity $entity, $with = [])
    {
        parent::__construct($entity);
        $this->with = $with;
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
        $values = $this->fieldValues(false);

        return sprintf(
            "insert into `%s` (%s) values (%s)",
            $this->mapper->getTable(),
            implode(", ", array_map(function ($value) { return "`$value`"; }, array_keys($values))),
            implode(", ", array_values($values))
        );
    }
}