<?php

namespace Bitmap\Query\Clauses;

class Where
{
    protected $table;
    protected $column;
    protected $operation;
    protected $value;

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     *
     * @return  Where
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param mixed $column
     *
     * @return  Where
     */
    public function setColumn($column)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param mixed $operation
     *
     * @return Where
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    public function getNumberOfParameters() {
        return is_array($this->value) ? count($this->value) : 1;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return Where
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}