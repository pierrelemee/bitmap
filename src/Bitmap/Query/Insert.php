<?php

namespace Bitmap\Query;

use Bitmap\Entity;

class Insert extends ModifyQuery
{
    protected $entity;

    public function __construct(Entity $entity)
    {
        parent::__construct($entity->getMapper());
        $this->entity = $entity;
    }

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
        $values = [];
        foreach ($this->mapper->getFields() as $name => $field) {
            $value = $field->get($this->entity);

            if (null !== $value) {
                $values[$field->getColumn()] = $value;
            } else {
                if (!$field->isIncremented() || !$field->hasDefault()) {
                    $values[$field->getColumn()] = "null";
                }
            }
        }

        foreach ($this->mapper->associations() as $name => $association) {
            if ($association->getMapper()->hasPrimary()) {
                $entity = $association->get($this->entity);

                if (null !== $entity) {
                    $values[$association->getName()] = $association->getMapper()->getPrimary()->get($entity);
                }
            }
        }

        return sprintf(
            "insert into `%s` (%s) values (%s)",
            $this->mapper->getTable(),
            implode(", ", array_keys($values)),
            implode(", ", array_values($values))
        );
    }
}