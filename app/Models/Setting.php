<?php

namespace App\Models;

class Setting extends Model
{

    protected $fillable = ['name', 'value', 'type'];
    protected static $cache = [];

    const TYPE_ARRAY = 'array';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_NUMBER = 'number';

    public function scopeSystem($query)
    {
        return $query->where('system', 1);
    }

    public function scopeUserDefined($query)
    {
        return $query->where('system', 0);
    }

    /**
     * Get settings
     * @param type $name Name of setting
     * @param type $cast Type to cast to. number, boolean and array supported
     * @return type Value
     */
    public static function get($name, $default = null, $cast = null)
    {
        //Check cache
        if (isset(self::$cache[$name])) {
            return self::$cache[$name];
        }

        $setting = self::where('name', $name)->first();
        if (is_object($setting)) {
            $type = $cast ?: $setting->type;
            switch ($type) {
                case static::TYPE_NUMBER:
                    $value = is_numeric($setting->value) ? floatval($setting->value) : $setting->value;
                case static::TYPE_BOOLEAN:
                    $value = boolval($setting->value);
                case static::TYPE_ARRAY:
                    $value = json_decode($setting->value, true);
                default:
                    $value = $setting->value;
            }
            self::$cache[$name] = $value;
            return $value;
        } else {
            return $default;
        }
    }

    public static function set($name, $value, $type = null, $system = 1)
    {
        if (is_array($value)) {
            $value = json_encode($value);
            $type = 'array';
        }

        $setting = self::where('name', $name)->first();
        if (!is_object($setting)) {
            $setting = new Setting;
            $setting->name = $name;
            $setting->label = str_replace('_', ' ', $name);
            $setting->system = $system;
        }
        $setting->value = $value;
        if (isset($type)) {
            $setting->type = $type;
        }
        $setting->save();
    }

}
