<?php
App::uses('AppHelper', 'View/Helper');
App::uses('CakeTime', 'Utility');

class PrettyTimeHelper extends AppHelper {
    private $monthes_rus = array(
		1 => 'января',
		2 => 'февраля',
		3 => 'марта',
		4 => 'апреля',
		5 => 'мая',
		6 => 'июня',
		7 => 'июля',
		8 => 'августа',
		9 => 'сентября',
		10 => 'октября',
		11 => 'ноября',
		12 => 'декабря',
 	);
	
	private $monthes_eng = array(
		1 => 'January',
		2 => 'February',
		3 => 'March',
		4 => 'April',
		5 => 'May',
		6 => 'June',
		7 => 'July',
		8 => 'August',
		9 => 'September',
		10 => 'October',
		11 => 'November',
		12 => 'December',
 	);

    public function pretty($date) {
		$lang = Configure::read('Config.language');
		$monthes_var = "monthes_".$lang;
		
        switch($date) {
            case CakeTime::isToday($date) :
                $date = __d("sweet_forum", "Today at")." ".CakeTime::format('H:i', $date);
            break;
            case CakeTime::wasYesterday($date) :
                $date = __d("sweet_forum", "Tomorrow at")." ".CakeTime::format('H:i', $date);
            break;
            case CakeTime::isThisYear($date) :
                $day = (int) CakeTime::format('d', $date);
                $month = (int) CakeTime::format('m', $date);
                $date = $day." ".$this->{$monthes_var}[$month]." ".__d("sweet_forum", "at")." ".CakeTime::format('H:i', $date);
            break;
            default :
                $date = CakeTime::format('d.m.Y H:i', $date);
            break;
        }
        return $date;
    }
}