<?php
/**
 * Author: Pierre Lemée
 */

namespace Bitmap\Query;

use Bitmap\Entity;

abstract class ModifyEntityQuery extends ModifyQuery
{
    protected $entity;

    public function __construct(Entity $entity)
    {
        parent::__construct($entity->getMapper());
        $this->entity = $entity;
    }

    protected function fieldValues($associations = true)
    {
        $values = [];
        foreach ($this->mapper->getFields() as $name => $field) {
            $value = $field->get($this->entity);

            if (null !== $value) {
                $values[$field->getName()] = $value;
            } else {
                if (!$field->isIncremented() || !$field->hasDefault()) {
                    $values[$field->getName()] = "null";
                }
            }
        }

        if ($associations) {
            foreach ($this->mapper->associations() as $name => $association) {
                if ($association->getMapper()->hasPrimary()) {
                    $entity = $association->get($this->entity);

                    if (null !== $entity) {
                        $values[$association->getName()] = $association->getMapper()->getPrimary()->get($entity);
                    }
                }
            }
        }

        return $values;
    }
}