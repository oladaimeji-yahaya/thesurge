<?php

/*
 * Copyright 2017 Y!Bambara.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Models;

use App\Models\Traits\ModelHelpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Description of Model
 *
 * @author Y!Bambara
 */
abstract class Model extends EloquentModel {

    use ModelHelpers;

    protected static $handlers = [
        'creating' => [],
        'created' => [],
        'deleting' => [],
        'deleted' => [],
        'updating' => [],
        'updated' => [],
    ];

    function __construct(array $attributes = [])
    {
        //Boot traits
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (ends_with($method, 'TraitBoot')) {
                $this->$method();
            }
        }

        parent::__construct($attributes);
    }

    protected static function boot()
    {
        static::creating(function ($object) {
            return self::executeHandlers($object, self::$handlers['creating'], true);
        });

        static::created(function ($object) {
            self::executeHandlers($object, self::$handlers['created']);
        });

        static::deleting(function ($object) {
            return self::executeHandlers($object, self::$handlers['deleting'], true);
        });

        static::deleted(function ($object) {
            self::executeHandlers($object, self::$handlers['deleted']);
        });

        static::updating(function ($object) {
            return self::executeHandlers($object, self::$handlers['updating'], true);
        });

        static::updated(function ($object) {
            self::executeHandlers($object, self::$handlers['updated']);
        });

        return parent::boot();
    }

    private static function executeHandlers($object, $handlers, $returns = false)
    {
        $return = true;
        foreach ($handlers as $key => $handler) {
            $result = $handler($object);
            if (!isset($result) && $returns) {
                throw new Exception("Handler $key returned null, true or false expected.");
            } else {
                $return &= $result;
            }
        }
        return $return;
    }

    protected static function addModelHandler($event, $closure, $name = null)
    {
        if ($name) {
            self::$handlers[$event][$name] = $closure;
        } else {
            self::$handlers[$event][] = $closure;
        }
    }

    protected static function removeModelHandler($event, $name)
    {
        unset(self::$handlers[$event][$name]);
    }

    public function scopePaid($query)
    {
        return $query->where($this->getTable() . '.paid_at', '<>', null);
    }

    public function scopeUnpaid($query)
    {
        return $query->where($this->getTable() . '.paid_at', null);
    }

    public function scopeUsed($query)
    {
        return $query->where($this->getTable() . '.used', 1);
    }

    public function scopeUnused($query)
    {
        return $query->where($this->getTable() . '.used', 0);
    }

    public function scopeComplete($query)
    {
        return $query->where($this->getTable() . '.is_complete', 1);
    }

    public function scopeIncomplete($query)
    {
        return $query->where($this->getTable() . '.is_complete', 0);
    }

    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where($this->getTable() . '.status', 0);
    }

    public function scopeMatched($query)
    {
        return $query->where($this->getTable() . '.matched', '>', 0);
    }

    public function scopeUnmatched($query)
    {
        return $query->where($this->getTable() . '.matched', '<=', 0);
    }

    public function scopeVerified($query)
    {
        return $query->where($this->getTable() . '.verified_at', '<>', null);
    }

    public function scopeUnverified($query)
    {
        return $query->where($this->getTable() . '.verified_at', null);
    }

    public function scopeExpired($query)
    {
        return $query->where($this->getTable() . '.expire_at', '<', Carbon::now());
    }

    public function scopeUnexpired($query)
    {
        return $query->where($this->getTable() . '.expire_at', '>=', Carbon::now());
    }

    public function scopeDue($query)
    {
        return $query->where(function($query) {
                    return $query->where($this->getTable() . '.due_at', '<', Carbon::now())
                                    ->orWhere($this->getTable() . '.due_at', null);
                });
    }

    public function scopeUndue($query)
    {
        return $query->where($this->getTable() . '.due_at', '>=', Carbon::now())
                        ->where($this->getTable() . '.due_at', '<>', null);
    }

}
