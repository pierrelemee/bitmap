<?php

namespace Bitmap\Associations\ManyToMany;

use Bitmap\Exceptions\MapperException;

class Via
{
    private static $KEYS = ['table', 'source', 'target'];

    protected $table;
    protected $sourceColumn;
    protected $targetColumn;

    private function __construct($table, $sourceColumn = null, $targetColumn = null)
    {
        $this->table = $table;
        $this->sourceColumn = $sourceColumn;
        $this->targetColumn = $targetColumn;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return mixed
     */
    public function getSourceColumn()
    {
        return $this->sourceColumn;
    }

    /**
     * @param mixed $sourceColumn
     *
     * @return Via
     */
    public function setSourceColumn($sourceColumn)
    {
        $this->sourceColumn = $sourceColumn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetColumn()
    {
        return $this->targetColumn;
    }

    /**
     * @param mixed $targetColumn
     *
     * @return Via
     */
    public function setTargetColumn($targetColumn)
    {
        $this->targetColumn = $targetColumn;
        return $this;
    }

    public static function fromTable($table) {
        return new Via($table);
    }


    public static function fromArray(array $values) {
        // Key indexed array
        if (isset($values['table'])) {
            return new Via($values['table'], isset($values['source']) ? $values['source'] : null, isset($values['target']) ? $values['target'] : null);
        }

        // Integer indexed array
        if (isset($values[0])) {
            return new Via($values[0], isset($values[1]) ? $values[1] : null, isset($values[2]) ? $values[2] : null);
        }

        return null;
    }

    /**
     * @param string $annotation
     *
     * @return Via
     */
    public static function fromAnnotation($annotation) {
        if (preg_match("/.+\(.+,.+\)$/", $annotation)) {
            $values = preg_split("/(\(|,|\))/", $annotation);
            return self::fromArray($values);
        }

        return null;
    }
}