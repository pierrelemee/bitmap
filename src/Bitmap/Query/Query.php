<?php

namespace Bitmap\Query;

use Bitmap\Mapper;
use PDO;

abstract class Query
{
    const VALUES_LIST_DELIMITER = ", ";

    protected $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public abstract function execute(PDO $connection);

    /**
     * @return string
     */
    public abstract function sql();

    protected function sqlValues(array $values, $delimiter = self::VALUES_LIST_DELIMITER)
    {
        $sql = [];

        foreach ($values as $name => $value) {
            $sql[] = sprintf("`%s` = %s", $name, $value);
        }

        return implode($delimiter, $sql);
    }
}