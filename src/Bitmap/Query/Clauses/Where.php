<?php

namespace Bitmap\Query\Clauses;

use Bitmap\Query\Clause;

class Where implements Clause
{
    protected $table;
    protected $column;
    protected $operation;
    protected $value;

    function toSQL()
    {
        return sprintf(
            "`%s`.`%s` %s %s",
            $this->table,
            $this->column,
            $this->operation,
            $this->value
        );
    }

}