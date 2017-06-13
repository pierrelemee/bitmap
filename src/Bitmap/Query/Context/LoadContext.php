<?php

namespace Bitmap\Query\Context;

use Bitmap\Association;

class LoadContext extends Context
{
    protected function isAssociationDefaultIncluded(Association $association)
    {
        return $association->isAutoloaded();
    }

    protected function children($mapper = null, $with = null, $parent = null, $depth = 0)
    {
        return new LoadContext($mapper, $with, $parent, $depth);
    }
}