<?php
App::uses('SweetForumAppController', 'SweetForum.Controller');

class PagesController extends SweetForumAppController {
	public $uses = array();

	function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('homepage', 'robots', 'load'));
	}

	function homepage() {
		$this->loadModel('SweetForum.Thread');
		$this->loadModel('SweetForum.Blog');

		$threads = $this->Thread->find('all',
			array(
				'fields' => array('Thread.name', 'Thread.description', 'Thread.url'),
				'contain' => false,
				'cache_options' => array()
			)
		);

		$blog = $this->Blog->find('first',
			array(
				'fields' => array('Blog.url', 'Blog.name'),
				'order' => 'Blog.id DESC',
				'cache_options' => array('5_min')
			)
		);

		$this->set(array(
			'threads' => $threads,
			'blog' => $blog
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
