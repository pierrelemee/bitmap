<?php

namespace Bitmap\Query\Context;

class ReferenceContext extends LoadContext
{
    protected $source;

    public function __construct($mapper, LoadContext $source, LoadContext $parent = null)
    {
        parent::__construct($mapper, $parent);

        $this->source = $source;
    }

    public function getTables()
    {
        return [];
    }

    public function getFields()
    {
        return [];
    }

    public function getJoins()
    {
        return [];
    }

    public function getDepth()
    {
        return $this->source->depth;
    }

    public function getJoinsOn(LoadContext $context, $name)
    {
        return [];
    }

}