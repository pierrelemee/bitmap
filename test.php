<?php

include __DIR__ . '/vendor/autoload.php';

use PierreLemee\Bitmap\Bitmap;
use PierreLemee\Bitmap\Entity;
use PierreLemee\Bitmap\Mapper;
use PierreLemee\Bitmap\Fields\MethodField;
use PierreLemee\Bitmap\Fields\AttributeField;



Bitmap::addConnection('chinook', $dsn);




$sql = 'select * from `Artist` where name like "The%"';

var_dump(Artist::select($sql));