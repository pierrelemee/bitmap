<?php
/**
 * Author: Pierre Lemée
 */

namespace Bitmap\Query;

use Bitmap\Entity;
use Bitmap\Query\Context\Context;

abstract class ModifyEntityQuery extends ModifyQuery
{
    protected $entity;
    /**
     * @var Context
     */
    protected $context;

    public function __construct(Entity $entity, $context = null)
    {
        parent::__construct($entity->getMapper());
        $this->entity = $entity;
        $this->context = $context;
    }

    protected function fieldValues($includeAssociations = true)
    {
        $values = [];
        foreach ($this->mapper->getFields() as $name => $field) {
            $value = $field->get($this->entity);

            if (null !== $value) {
                $values[$field->getName()] = $value;
            } else {
                if (!$field->isIncremented() && !$field->hasDefault()) {
                    $values[$field->getName()] = "null";
                }
            }
        }

        if ($includeAssociations) {
            foreach ($this->mapper->associations() as $name => $association) {
                if ($association->hasLocalValue() && $association->getMapper()->hasPrimary() || $this->context->hasDependency($association->getName())) {
                    $entities = is_array($association->get($this->entity)) ? $association->get($this->entity) : [$association->get($this->entity)];
                    foreach ($entities as $entity) {
                        if (null !== $entity) {
                            $values[$association->getColumn()] = $association->getMapper()->getPrimary()->get($entity);
                        }
                    }
                }
            }
        }

        return $values;
    }
}