<?php

namespace Bitmap\Query\Context;

use Bitmap\Association;

class SaveContext extends Context
{
    public function __construct($mapper = null, $with = null, $parent = null)
    {
        parent::__construct($mapper, $parent);

        if (is_array($with)) {
            foreach ($with as $name => $value) {
                if (is_int($name)) {
                    $name = $value;
                    $subcontext = [];
                } else {
                    $subcontext = $value;
                }

                $association = $this->getMapper()->getAssociation($name);
                $this->dependencies[$association->getName()] = new SaveContext($association->getMapper(), $subcontext, $this);
            }
        }

        if (is_null($with)) {
            foreach ($this->mapper->associations() as $association) {
                if (!isset($this->dependencies[$association->getName()])) {
                    if ($association->isAutosaved()) {
                        $this->dependencies[$association->getName()] = new SaveContext($association->getMapper(), [], $this);
                    }
                }
            }
        }
    }
}