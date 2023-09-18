<?php

namespace App\Models;

use DateTime;

class SetDueDate
{
    /**
     * возвращает строку времени в формате unix 
     * через 4 дня (с учётом рабочего времени и дня недели)
     * @return int
     */
    static function set() {
        date_default_timezone_set('Asia/Tashkent');
        $date = new DateTime('+4 days');
        $day = $date->format('l');
        $time = $date->format('H:i');

        if( $date->format('N') >= 6 ) {
            $day = 'Monday';
        }
        if(($time < '09:00') || ($time > '18:00')) {
            $time = '10:00';
        }

        return strtotime($day.' '.$time);
    }
}
