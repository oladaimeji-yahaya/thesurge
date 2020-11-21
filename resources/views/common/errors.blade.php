<?php
/*
 * Copyright 2016 Y!Bambara.
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
?>
@if (count($errors) > 0)
<!--Form Error List-->
<div class = "alert alert‐danger">
    <strong>Oops! Something went wrong</strong>
    <br><br>
    <ul>
        @foreach ($errors‐>all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div
@endif