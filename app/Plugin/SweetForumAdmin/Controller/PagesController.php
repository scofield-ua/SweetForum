<?php
//App::uses('SweetForumAdminAppController', 'SweetForumAdmin.Controller');

class PagesController extends SweetForumAdminAppController {
	public $uses = array();

	function dashboard() {
		$this->loadModel('SweetForum.Topic');
		$this->loadModel('SweetForum.Comment');
		$this->loadModel('SweetForum.User');

		$topics = $this->Topic->find('count');
		$comments = $this->Comment->find('count');
		$users = $this->User->find('count');

		$this->set(array(
			'page_title' => __d('sweet_forum', 'Admin dashboard'),
			'current_nav' => "/admin",
			'topics' => $topics,
			'comments' => $comments,
			'users' => $users
		));
	}
}
