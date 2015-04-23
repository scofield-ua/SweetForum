<?php
class GeoComponent extends Component {
	function getIp() {
		if(!empty($_SERVER['HTTP_CLIENT_IP']))  {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}		
		return $ip;
	}
		
	function getTimezone() {		
		$ip = $this->getIp();
		$c_n = 'timezone_for_'.md5($ip);
		$c_d = 'very_long';
		$timezone = '';
		if(!$geo = Cache::read($c_n, $c_d)) {
			$geo = @file_get_contents('http://smart-ip.net/geoip-json/'.$ip);			
			if($geo !== false) {			
				$geo = json_decode($geo, true);
				if(array_key_exists('timezone', $geo)) $timezone = $geo['timezone'];			
			}
			Cache::write($c_n, $geo, $c_d);
		} else {
			$timezone = $geo['timezone'];
		}
		$timezone = trim($timezone);		
		if(empty($timezone)) $timezone = "Europe/Moscow";
		return $timezone;
	}
}