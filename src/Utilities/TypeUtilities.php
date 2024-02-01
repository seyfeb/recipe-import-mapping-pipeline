<?php

namespace Utilities;

class TypeUtilities {
	/**
	 * Ensures that item is an array. If not, wraps it in an array.
	 * @template T
	 * @param T $value Item to be wrapped in an array if it isn't an array itself.
	 * @return array<T>
	 */
	public static function as_array(mixed $value): array
	{
        return is_array($value) ? $value : [$value];
	}

	/**
	 * Ensures that item is an array. If not, wraps it in an array. Removes all `null` or `undefined` values.
	 * @template T
	 * @param T $value Item to be wrapped in an array if it isn't an array itself.
	 * @return array<T>
	 */
	public static function as_cleaned_array(mixed $value): array
	{
        $arr = self::as_array($value);
        return array_filter($arr, function($v, $k) { return !!$v; }, ARRAY_FILTER_USE_BOTH);
	}
}
