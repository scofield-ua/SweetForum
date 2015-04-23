<?php
class UsersController extends SweetForumAdminAppController {
	public $uses = array('SweetForum.User', 'SweetForum.UserReport');
	public $helpers = array('Time');

	function index() {
		$conds = array();

		if(array_key_exists('email', $this->request->query)) {
			$email = trim($this->request->query['email']);

			if(!empty($email)) {
				$this->set(array('search_email' => $email));

				$conds['User.email LIKE'] = "%{$email}%";
			}
		}

		$this->paginate = array(
			'conditions' => $conds,
			'contain' => array('Info'),
			'order' => 'User.id DESC',
			'limit' => 100
		);

		$users = $this->paginate('User');

		$this->set(array(
			'page_title' => __d('sweet_forum', 'Users'),
			'current_nav' => '/admin/users/index',
			'users' => $users
		));
	}

	function edit($id = null) {
		if($id === null) throw new NotFoundException();

		$this->User->recursive = 1;
		$user = $this->User->findById($id);

		if($this->request->is('post')) {
			$this->User->Info->id = $user['Info']['id'];

			if($this->User->Info->save($this->request->data)) {
				$this->Session->setFlash(__d("sweet_forum", "User updated"), 'default', array('class' => 'alert alert-success margin-top15'));
			} else {
				$this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
			}
		}

		$this->set(array(
			'page_title' => __d('sweet_forum', 'Edit user'),
			'current_nav' => '/admin/users/index',
			'user' => $user
		));
	}

	function group($id = null) {
		if($id === null) throw new NotFoundException();
		
		$user = $this->User->findById($id);
		if(empty($user)) throw new NotFoundException();
		
		$groups = array(
			'user' => __d('sweet_forum', 'Users'),
			'admin' => __d('sweet_forum', 'Admins'),
			'moderator' => __d('sweet_forum', 'Moderators'),
		);

		if($this->request->is('post')) {
			if(array_key_exists($this->request->data['User']['role'], $groups)) {
				$this->User->id = $id;
				if(!$this->User->saveField('role', $this->request->data['User']['role'])) echo 1;

				$this->Session->setFlash(__d("sweet_forum", "User group updated"), 'default', array('class' => 'alert alert-success margin-top15'));
				$this->redirect($this->here);
			} else {
				$this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
			}
		}

		$this->set(array(
			'page_title' => __d('sweet_forum', 'User group'),
			'groups' => $groups,
			'user' => $user,
			'current_nav' => '/admin/users/index',
		));
	}
	
	function reports() {
		$this->paginate = array(
			'limit' => 50,
			'contain' => array('User' => array('Info'), 'ByUser' => array('Info'))
		);
		
		$reports = $this->paginate($this->UserReport);
		
		$this->set(array(
			'page_title' => __d('sweet_forum', 'Users reports'),
			'reports' => $reports,
			'current_nav' => '/admin/users/reports',
		));
	}
	
	function ban($user_id = null) {
		if(!$this->request->is('post')) throw new NotFoundException();
		
		$this->autoRender = false;
		
		if($user_id === null) throw new NotFoundException();
		
		$user = $this->User->findById($user_id);
		if(empty($user)) throw new NotFoundException();
		
		$value = (int) $user['User']['status'] == -1 ? 0 : -1; // ban or unban
		
		$this->User->id = $user_id;
		$this->User->saveField('status', $value);
		
		echo $value == -1 ? __d('sweet_forum', 'User successfully baned') : __d('sweet_forum', 'User successfully unbaned');
	}
	
	function banned() {
		$this->paginate = array(
			'conditions' => array('User.status' => -1),
			'contain' => array('Info'),
			'limit' => 50,
		);
		
		$users = $this->paginate($this->User);
		
		$this->set(array(
			'page_title' => __d('sweet_forum', 'Banned users'),
			'users' => $users,
			'current_nav' => '/admin/users/banned',
		));
	}

	/*function setup_acl_groups() {
		$this->autoRender = false;

		$aro = $this->Acl->Aro;

		// Here's all of our group info in an array we can iterate through
		$groups = array(
			0 => array(
				'alias' => 'Admins'
			),
			1 => array(
				'alias' => 'Users'
			),
			2 => array(
				'alias' => 'Moderators'
			),
		);

		// Iterate and create ARO groups
		foreach ($groups as $data) {
			// Remember to call create() when saving in loops...
			$aro->create();

			// Save data
			$aro->save($data);
		}
	}*/

	function aco() {
		$this->autoRender = false;

		/*$aco = $this->Acl->Aco;

		$data = array(
			'parent_id' => 2,
			'alias' => 'Pages'
		);

		$aco->create($data);
		$aco->save();*/
	}

	function aco_aro() {
		$this->autoRender = false;


	}
}