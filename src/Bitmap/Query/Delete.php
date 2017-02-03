<?php

namespace Bitmap\Query;

use Bitmap\Entity;

class Delete extends ExecQuery
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
        return new Delete($entity);
    }


    public function sql()
    {
        return sprintf(
            "delete from `%s` where `%s` = %s",
            $this->mapper->getTable(),
            $this->mapper->getPrimary()->getName(),
            $this->mapper->getPrimary()->get($this->entity)
        );
    }
}