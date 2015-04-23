<?php
$eng = 'File';
$path = CACHE.'sweet_forum'.DS;
$pref = 'sf';

Cache::config($pref.'_default', array(
    'engine' => $eng,
    'prefix' => 'cake_'.$pref.'_default_',
    'path' => $path,
    'serialize' => true,
    'duration' => '+30 days',
));

Cache::config($pref.'_long', array(
    'engine' => $eng,
    'path' => $path,
    'prefix' => 'cake_'.$pref.'_1_long_',
    'serialize' => true,
    'duration' => '+365 days',
));

Cache::config($pref.'_1_min', array(
    'engine' => $eng,
    'path' => $path,
    'prefix' => 'cake_'.$pref.'_1_min_',
    'serialize' => true,
    'duration' => '+1 minute',
));

Cache::config($pref.'_1_day', array(
    'engine' => $eng,
    'path' => $path,
    'prefix' => 'cake_'.$pref.'_1_day_',
    'serialize' => true,
    'duration' => '+1 days',
));

define("SWEET_FORUM_BASE_URL", "/");