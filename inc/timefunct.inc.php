<?php

function checkForDaily($time) {
    $currenttime = time();
    $oneday = 60 * 60 * 24;
    $output = $currenttime + $oneday;

    if($time < $output) {
        return true;
    } else {
        return false;
    }
}