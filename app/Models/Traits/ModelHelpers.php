<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class ModelHelpers
 *
 * @package App\Models\Traits
 */
trait ModelHelpers
{
    /**
     * @param null $class
     *
     * @return mixed
     */
    public static function morphKey($class = null)
    {
        $morphMap = array_flip(Relation::morphMap());

        return $morphMap[ $class ?: self::class ];
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public static function morphClass($key)
    {
        $morphMap = Relation::morphMap();

        return $morphMap[ $key ];
    }

    /**
     * @param $column
     * @param $value
     *
     * @return mixed
     */
    public static function findByColumn($column, $value)
    {
        return self::where($column, $value);
    }

    /**
     * @return array
     */
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

}
