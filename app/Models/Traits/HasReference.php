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

namespace App\Models\Traits;

/**
 *
 * @author Y!Bambara
 */
trait HasReference {

    protected function hasReferenceTraitBoot()
    {
        static::addModelHandler('created', function ($reference) {
            $class = get_class();
            if ($reference instanceof $class) {
                $s = explode('\\', $class);
                $clasname = array_pop($s);
                $reference->reference = uniqid(substr($clasname, 0, 2));
                $reference->save();
            }
        });
    }

    public static function findByReference($ref)
    {
        return self::where('reference', $ref)->first();
    }

}
