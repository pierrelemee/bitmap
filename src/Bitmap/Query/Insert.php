<?php

namespace Bitmap\Query;

use Bitmap\Entity;

class Insert extends Query
{
    protected $entity;

    public function __construct(Entity $entity)
    {
        parent::__construct($entity->getMapper());
        $this->entity = $entity;
    }

    /**
     * @{@inheritdoc}
     * @param string $class
     *
     * @return Delete
     */
    public static function fromEntity(Entity $entity)
    {
        return new Insert($entity);
    }


    public function sql()
    {
        $values = $this->mapper->values($this->entity);
        return sprintf(
            "insert into `%s` (%s) values (%s)",
            $this->mapper->getTable(),
            implode(", ", array_keys($values)),
            implode(", ", array_values($values))
        );
    }
}