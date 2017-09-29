<?php

namespace Bitmap\Query;

interface Clause
{
    function toSQL();
}