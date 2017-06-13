<?php

namespace Bitmap\Query\Context;

use Bitmap\Association;

class SaveContext extends Context
{
    protected function isAssociationDefaultIncluded(Association $association)
    {
        return $association->isAutosaved();
    }

    protected function children($mapper = null, $with = null, $parent = null, $depth = 0)
    {
        return new SaveContext($mapper, $with, $parent, $depth);
    }
}