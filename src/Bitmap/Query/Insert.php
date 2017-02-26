<?php

namespace Bitmap\Query;

use Bitmap\Entity;

class Insert extends ModifyEntityQuery
{
    /**
     * @{@inheritdoc}
     * @param string $class
     *
     * @return Delete
     */
    public static function fromEntity(Entity $entity)
    {
        return new Insert($entity);
    }


    public function sql()
    {
        $values = $this->fieldValues();

        return sprintf(
            "insert into `%s` (%s) values (%s)",
            $this->mapper->getTable(),
            implode(", ", array_keys($values)),
            implode(", ", array_values($values))
        );
    }
}