<?php
App::uses('SweetForumAppController', 'SweetForum.Controller');

class PagesController extends SweetForumAppController {
	public $uses = array();
	public $components = array('SweetForum.Geo');

	function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('homepage', 'robots', 'load', 'install'));
	}

	function homepage() {
		$this->loadModel('SweetForum.Thread');

		$threads = $this->Thread->find('all',
			array(
				'fields' => array('Thread.name', 'Thread.description', 'Thread.url'),
				'contain' => false,
				'cache_options' => array()
			)
		);

		$this->set(array(
			'threads' => $threads,
		));
	}
	
	/**
	*	Install page
	*/
	function install() {
		$this->loadModel('SweetForum.Setting');
		
		$settings = $this->Setting->find('first');
		
		if((bool) $settings['Setting']['is_installed']) throw new NotFoundException();
		
		if($this->request->is('post')) {
			$this->loadModel('SweetForum.User');
			$this->loadModel('SweetForum.Thread');
						
			$this->request->data['Thread']['url'] = md5(date('YmdHis'));
			
			$this->User->set($this->request->data['User']);
			$this->Thread->set($this->request->data['Thread']);
			
			if($this->User->validates() && $this->Thread->validates()) {				
				$email_hash = md5(strtolower(trim($this->request->data['User']['email'])));
				$photo = "http://www.gravatar.com/avatar/{$email_hash}";
				
				$data = array(
					'User' => array(
						'hash_id' => md5(date('YmdHis').Configure::read('Security.salt').rand(1,100).microtime()),
						'email' => $this->request->data['User']['email'],
						'password' => Security::hash($this->request->data['User']['password'], 'blowfish'),
						'ip' => $this->Geo->getIp(),
						'role' => 'admin',
					),
					'Info' => array(
						'username' => md5(date('dmYHis').microtime().$this->request->data['User']['email']),
						'name' => $this->request->data['Info']['name'],
						'avatar' => $photo
					),
					'Privacy' => array(
						'show_social_link' => true
					),
					'Notification' => array(
						'new_topic_comment' => true
					)
				);
				
				$user_saved = $this->User->saveAssociated($data);
				$this->Thread->save($this->request->data['Thread']);
				
				$this->Setting->id = $settings['Setting']['id'];
				$this->Setting->saveField('is_installed', true);
				
				if($user_saved) {
					$this->User->recursive = 1;
					$data = $this->User->findByEmail($this->request->data['User']['email']);
					
					unset($data['User']['password']);
					$this->Auth->login($data);
					
					$this->redirect(SWEET_FORUM_BASE_URL.'admin');
				}
			}
		}
		
		$this->set(array(
			'page_title' => __d('sweet_forum', 'Welcome'),
			'settings' => $settings,
			'light_header' => true,
		));
	}

	function load() {
		$this->autoRender = false;

		$allowed_elements = array('parts/social-links');

		$path = "";
		if(array_key_exists('path', $this->request->data)) {
			$path = trim($this->request->data['path']);
		}

		if(empty($path)) throw new NotFoundException();
		if(!in_array($path, $allowed_elements)) throw new NotFoundException();

		$view = new View($this, false);
		return $view->element($path);
	}
}
