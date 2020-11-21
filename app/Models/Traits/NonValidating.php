<?php

namespace App\Models\Traits;

/**
 * Class NonValidating
 *
 * @package App\Models\Traits
 */
trait NonValidating {

    /**
     * @param array $array
     *
     * @return bool
     */
    public static function validate(array $array)
    {
        return true;
    }

}
