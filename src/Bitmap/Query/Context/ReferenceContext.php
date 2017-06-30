<?php

namespace Bitmap\Query\Context;

use Bitmap\FieldMappingStrategy;

class ReferenceContext extends Context
{
    protected $source;

    public function __construct($mapper, Context $source, $parent = null)
    {
        parent::__construct($mapper, null, $parent);

        $this->source = $source;
    }

    protected function initialize($with = null)
    {

    }

    public function getTables()
    {
        return [];
    }

    public function getFields(FieldMappingStrategy $strategy)
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