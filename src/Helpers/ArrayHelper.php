<?php

namespace Studio89\WP\Composer\Helpers;

/**
 * Class ArrayHelper
 *
 * @package Studio89\WP\Composer\Helpers
 */
class ArrayHelper
{
    /**
     * @param array $array
     * @param $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function getValue( array $array, $key, $default = null )
    {
        return isset( $array[ $key ] ) ? $array[ $key ] : $default;
    }
}