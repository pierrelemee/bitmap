<?php

namespace Bitmap\Query\Context;

use Bitmap\Association;

class SaveContext extends QueryContext
{
    protected function isAssociationDefaultIncluded(Association $association)
    {
        return $association->isAutosaved();
    }

    protected function children($mapper, $parent = null, $with = null)
    {
        return new SaveContext($mapper, $with, $parent);
    }


}