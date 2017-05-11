<?php

namespace Bitmap\Transformers;

use Bitmap\Bitmap;
use Bitmap\Transformer;

class BooleanTransformer extends Transformer
{
	public function getName()
	{
		return Bitmap::TYPE_BOOLEAN;
	}

	public function toObject($value)
	{
		return $value > 0;
	}

	public function fromObject($value)
	{
		if (is_bool($value) || is_int($value)) {
			return $value ? "true" : "false";
		}

		if (is_string($value)) {
			return strtolower(trim($value)) === 'true' ? "true" : "false";
		}

		return "false";
	}

}