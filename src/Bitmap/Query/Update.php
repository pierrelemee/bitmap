<?php

namespace Bitmap\Query;

use Bitmap\Entity;

class Update extends ExecQuery
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
        return new Update($entity);
    }


    public function sql()
    {
        return sprintf(
            "update `%s` set %s where `%s` = %s",
            $this->mapper->getTable(),
            $this->sqlValues($this->mapper->values($this->entity)),
            $this->mapper->getPrimary()->getName(),
            $this->mapper->getPrimary()->get($this->entity)
        );
    }
}