<?php
/**
 * Author: Pierre Lemée
 */

namespace Bitmap\Query;

use Bitmap\Entity;

abstract class ModifyEntityQuery extends ModifyQuery
{
    protected $entity;
    protected $with = [];

    public function __construct(Entity $entity, $with = [])
    {
        parent::__construct($entity->getMapper());
        $this->entity = $entity;
        $this->with = $with;
    }

    protected function fieldValues()
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

        foreach ($this->mapper->associations() as $name => $association) {
            if ($association->hasLocalValue() && $association->getMapper()->hasPrimary() || null === $this->with || (is_array($this->with) && in_array($association->getName(), $this->with))) {
                $entity = $association->get($this->entity);

                if (null !== $entity) {
                    $values[$association->getName()] = $association->getMapper()->getPrimary()->get($entity);
                }
            }
        }

        return $values;
    }
}