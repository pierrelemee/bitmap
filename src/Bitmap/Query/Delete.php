<?php

namespace Bitmap\Query;

use Bitmap\Entity;
use PDO;

class Delete extends ModifyQuery
{
    protected $entity;

    public function __construct(Entity $entity)
    {
        parent::__construct($entity->createMapper());
        $this->entity = $entity;
    }

    /**
     * @{@inheritdoc}
     * @param string $class
     *
     * @return self
     */
    public static function fromEntity(Entity $entity)
    {
        return new Delete($entity);
    }

    protected function getStatement(PDO $connection)
    {
        $statement = $connection->prepare($this->sql($connection));

        $statement->execute([$this->mapper->getPrimary()->get($this->entity)]);

        return $statement;
    }

    public function sql(PDO $connection)
    {
        return sprintf(
            "delete from `%s` where `%s` = ?",
            $this->mapper->getTable(),
            $this->mapper->getPrimary()->getColumn()
        );
    }
}