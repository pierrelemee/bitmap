<?php

namespace Bitmap\Query;

use Bitmap\Bitmap;
use Bitmap\Entity;
use Exception;
use PDO;

class Delete extends Query
{
    protected $entity;

    public function __construct(Entity $entity)
    {
        parent::__construct($entity->createMapper());
        $this->entity = $entity;
    }

    public function execute(PDO $connection)
    {
        $sql = sprintf(
            "delete from %s where %s = ?",
            self::escapeName($this->mapper->getTable(), $connection),
            self::escapeName($this->mapper->getPrimary()->getColumn(), $connection)
        );

        $statement = $connection->prepare($sql);

        Bitmap::current()->getLogger()->info("Running query",
            [
                'mapper' => $this->mapper->getClass(),
                'sql'    => $sql,
                'values' => [$this->mapper->getPrimary()->getValue($this->entity)]
            ]
        );

        if (!$statement->execute([$this->mapper->getPrimary()->getValue($this->entity)])) {
            throw new Exception(sprintf("[%s]", implode(", ", array_values($statement->errorInfo())),  $statement->errorCode()));
        }

        return $statement->rowCount() === 1;
    }


    public function sql(PDO $connection)
    {
        return "";
    }
}