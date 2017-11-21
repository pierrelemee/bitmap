<?php

namespace Bitmap\Query\Clauses;

class Join
{
    protected $fromTable;
    protected $toTable;
    protected $toTableAlias;
    protected $fromColumn;
    protected $toColumn;

    /**
     * @return mixed
     */
    public function getFromTable()
    {
        return $this->fromTable;
    }

    /**
     * @param mixed $fromTable
     *
     * @return self
     */
    public function setFromTable($fromTable)
    {
        $this->fromTable = $fromTable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToTable()
    {
        return $this->toTable;
    }

    /**
     * @param mixed $toTable
     *
     * @return self
     */
    public function setToTable($toTable)
    {
        $this->toTable = $toTable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToTableAlias()
    {
        return $this->toTableAlias;
    }

    /**
     * @param mixed $toTableAlias
     *
     * @return self
     */
    public function setToTableAlias($toTableAlias)
    {
        $this->toTableAlias = $toTableAlias;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFromColumn()
    {
        return $this->fromColumn;
    }

    /**
     * @param mixed $fromColumn
     *
     * @return self
     */
    public function setFromColumn($fromColumn)
    {
        $this->fromColumn = $fromColumn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToColumn()
    {
        return $this->toColumn;
    }

    /**
     * @param mixed $toColumn
     *
     * @return self
     */
    public function setToColumn($toColumn)
    {
        $this->toColumn = $toColumn;

        return $this;
    }

    public static function create()
    {
        return new Join();
    }
}