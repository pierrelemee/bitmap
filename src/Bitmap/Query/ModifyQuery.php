<?php

namespace Bitmap\Query;

use PDO;

abstract class ModifyQuery extends Query
{
    public function execute(PDO $connection)
    {
        return $connection->exec($this->sql());
    }
}