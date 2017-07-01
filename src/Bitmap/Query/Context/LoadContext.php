<?php

namespace Bitmap\Query\Context;

use Bitmap\Association;

class LoadContext extends QueryContext
{
    protected function isAssociationDefaultIncluded(Association $association)
    {
        return $association->isAutoloaded();
    }

    protected function children($mapper, $parent = null, $with = null)
    {
        return new LoadContext($mapper, $with, $parent);
    }
}