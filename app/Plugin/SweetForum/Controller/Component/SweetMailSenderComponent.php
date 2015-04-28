<?php
App::uses('CakeEmail', 'Network/Email');
class SweetMailSenderComponent extends Component {
    /*
    *   Function for send letter to email
    *   @param string $to Email address to send
    *   @param array $data Helpful options
    *   @param string $data['from_email'] Author email
    *   @param string $data['from_name'] Author name
    *   @param string $data['subject']
    *   @param string $data['layout'] Layout for email letter
    *   @param array $data['data'] Variables for view files
    */
	function send($to, $data = array()) {		
        // default params
        $data['view'] = array_key_exists('view', $data) ? $data['view'] : 'default';
        $data['subject'] = array_key_exists('subject', $data) ? $data['subject'] : 'Subject';
        $data['data'] = array_key_exists('data', $data) ? $data['data'] : array();        
        $data['layout'] = array_key_exists('layout', $data) ? $data['layout'] : 'default';
        $data['theme'] = array_key_exists('theme', $data) ? $data['theme'] : null;        
        
		$email = new CakeEmail();
		$email->from(array($data['from_email']  => $data['from_name']));
		$email->emailFormat('html');
		$email->template($data['view'], $data['layout']);
        $email->theme($data['theme']);
		$email->helpers(array('Html'));		
		$email->to($to);				
		$email->subject($data['subject']);
		$email->viewVars($data['data']);
		$email->charset = "utf-8";
		$email->headerCharset = "utf-8";		
		
		return $email->send();
	}
}