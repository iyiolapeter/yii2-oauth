<?php

namespace pso\yii2\oauth\helpers;

use DateTime;
use Yii;

class TimestampHelper
{
    public static function toTimestamp($value){
        return strtotime($value);
    }

    public static function toDatetime($value, $format = 'Y-m-d H:i:s'){
        return date($format, $value);
    }
}