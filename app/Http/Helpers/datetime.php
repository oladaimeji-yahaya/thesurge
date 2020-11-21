<?php

use Carbon\Carbon;

function fancyTime($time)
{
    if (!$time instanceof Carbon) {
        if (is_int($time)) {
            $time = Carbon::createFromTimestamp($time);
        } else {
            $time = Carbon::createFromTimestamp(strtotime($time));
        }
    }
    if (Carbon::now()->diffInSeconds($time) < 60) {
        return 'just now';
    } else {
        return $time->diffForHumans();
    }
}
