<?php

namespace App\Models\Traits;

/**
 * Class FindBySlug
 *
 * @package App\Models\Traits
 */
trait HasSlug {

    /**
     * @param $string
     * @param $pattern
     *
     * @return mixed
     */
    public static function findBySlug($string, $pattern = false)
    {
        if ($pattern) {
            return self::where('slug', 'LIKE', $string);
        } else {
            return self::where('slug', $string);
        }
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @param $title
     *
     * @return string
     */
    public static function makeUniqueSlug($title)
    {
        $slug = self::makeSlug($title);
        $matches = self::findBySlug($slug . '%', true)->get()->count();
        if ($matches) {
            $slug .= '-' . $matches;
        }
        if (is_object(self::findBySlug($slug)->first())) {
            return self::makeUniqueSlug($slug);
        }

        return $slug;
    }

    /**
     * @param $string
     *
     * @return string
     */
    public static function makeSlug($string)
    {
        $string = str_slug($string);

        //truncate to max of 190 to match mysql table constraints
        return substr($string, 0, 188);
    }

}
