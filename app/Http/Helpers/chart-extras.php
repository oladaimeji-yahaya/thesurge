<?php

function monthNames()
{
    return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
}

function monthNamesSubStr($start = 0, $length = 3)
{
    $arr = monthNames();
    $arr = array_map(function ($m) use ($start, $length) {
        return substr($m, $start, $length);
    }, $arr);

    return $arr;
}

function extractObjectProperties($object, $properties = [])
{
    $results = [];
    foreach ($properties as $property) {
        $results[$property] = $object->$property;
    }

    return $results;
}

use Carbon\Carbon;

function lastNMonths($N, Carbon $start, $monthNames, $reverse = true)
{
    $results = [];
    for ($n = 0; $n < $N; $n++) {
        $results[$n] = $monthNames[$start->month - 1] . ' ' . $start->year;
        $start = $start->subMonth();
    }

    return $reverse ? array_reverse($results) : $results;
}

function addMonthTag(&$arr, $monthNames, $date_key)
{
    array_map(function ($group) use ($monthNames, $date_key) {
        $group->transform(function ($item) use ($monthNames, $date_key) {
            /**
             * @var \Carbon\Carbon $date
             */
            $date = $item->$date_key;
            $item->month = $monthNames[$date->month - 1] . ' ' . $date->year;

            return $item;
        });
    }, $arr);
}
