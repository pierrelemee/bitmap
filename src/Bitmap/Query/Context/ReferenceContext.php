<?php

namespace Bitmap\Query\Context;

class ReferenceContext extends Context
{
    protected $source;

    public function __construct($mapper, Context $source, $parent = null)
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


}