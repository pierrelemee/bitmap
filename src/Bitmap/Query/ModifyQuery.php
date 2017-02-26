<?php

namespace Bitmap\Query;

use Bitmap\Entity;
use PDO;

abstract class ModifyQuery extends Query
{
    public function execute(PDO $connection)
    {
        return $connection->exec($this->sql());
    }
}