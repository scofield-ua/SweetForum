<?php
class UsersController extends SweetForumAppController{
	public $components = array('SweetForum.Geo');

    function beforeFilter() {
        parent::beforeFilter();

		$this->Auth->allow(array('sign', 'view', 'signin', 'signup', 'password', 'password_reset'));

		$this->set(array(
			'current_back' => $this->_getCurrentBackUrl()
		));
    }

	private function _getCurrentBackUrl() {
		// current back url (if exists)
		$back = "/";
		if(array_key_exists('back', $this->params->query)) {
			$back = trim($this->params->query['back']);
			if(empty($back)) $back = SWEET_FORUM_BASE_URL;
		}

		return $back;
	}

	/*
	*	Функция обновляем пользоватские данные в сессии
	*/
	private function _updateUserSession() {
		$user_id = $this->Auth->user('User.id');
		$data = $this->User->findById($user_id);
		$allowed = array('Info.name', 'Info.username'); // поля, которые можно обновлять

		foreach($data as $key => $array) {
			foreach($array as $name => $value) {
				$n = "{$key}.{$name}";
				if(in_array($n, $allowed)) $this->Session->write("Auth.User.{$n}", (string) $value);
			}
		}
	}

	/*
	*	Social login
	*/
    function sign() {
		$this->autoRender = false;

		if($this->request->is('post')) {
			$this->Auth->logout();
			try {
				$s = file_get_contents('http://ulogin.ru/token.php?token='.$_POST['token'].'&host='.$_SERVER['HTTP_HOST']);
			} catch(Exception $e) {
				throw new BadRequestException(__d("sweet_forum", "Error"));
			}
			$user = json_decode($s, true);

			if(!isset($user['error'])) {
				$back = $this->_getCurrentBackUrl();

				// find user with such email
				$this->User->recursive = 1;
				$f = $this->User->findByEmail($user['email']);
				// if not exists than create
				if(empty($f)) {
					$email_hash = md5(strtolower(trim($user['email'])));
					$user['photo'] = "http://www.gravatar.com/avatar/{$email_hash}";

					$data = array(
						'User' => array(
							'hash_id' => md5(date('YmdHis').Configure::read('Security.salt').rand(1,100).microtime()),
							'email' => $user['email'],
							'password' => "0",
							'ip' => $this->Geo->getIp(),
							'role' => 'user',
							'status' => 0
						),
						'Info' => array(
							'username' => md5(date('dmYHis').microtime().$user['email']),
							'name' => $user['first_name'],
							'avatar' => $user['photo']
						),
						'Privacy' => array(
							'show_social_link' => true
						),
						'Notification' => array(
							'new_topic_comment' => true
						)
					);

					if(array_key_exists('profile', $user)) $data['Info']['social'] = $user['profile'];

					if($this->User->saveAssociated($data)) {
						unset($data['User']['password']);
						$data['User']['id'] = $this->User->id;
						$data['Info']['id'] = $this->User->Info->id;
						$this->Auth->login($data);
					}
				} else {
					$data = array(
						'User' => array(
							'id' => $f['User']['id'],
							'hash_id' => $f['User']['hash_id'],
							'email' => $f['User']['email'],
							'ip' => $f['User']['ip'],
							'role' => $f['User']['role'],
							'status' => $f['User']['status']
						),
						'Info' => array(
							'id' => $f['Info']['id'],
							'username' => $f['Info']['username'],
							'name' => $f['Info']['name'],
							'avatar' => $f['Info']['avatar'],
							'social' => $f['Info']['social']
						),
						'Privacy' => array(
							'show_social_link' => $f['Privacy']['show_social_link']
						)
					);
					$this->Auth->login($data);
				}
			}
			echo $this->redirect($back);
		}
	}

	function signin() {
		if($this->Auth->loggedIn()) $this->redirect($this->_getCurrentBackUrl());

		if($this->request->is('post')) {
			$data = $this->User->find('first',
				array(
					'conditions' => array('User.email' => $this->request->data['User']['email']),
					'contain' => array('Info', 'Privacy')
				)
			);

			if($this->Auth->login()) {
				unset($data['User']['password']);
				$this->Auth->login($data);
				$back = array_key_exists('back_url', $this->request->data['User']) ? urldecode($this->request->data['User']['back_url']) : $this->_getCurrentBackUrl();
				$this->redirect($back);
			} else {
				$this->set(array(
					"error_message" => __d("sweet_forum", "Password or email is incorrect"),
				));
			}
		}

		$this->set(array(
			"page_title" => __d("sweet_forum", "Sign In"),
			"light_header" => true,
			"hide_sign_modals" => true
		));
	}

	function signup() {
		if($this->Auth->loggedIn()) $this->redirect($this->_getCurrentBackUrl());

		if($this->request->is('post')) {
			$validate = $this->User->saveAll($this->request->data, array('validate' => 'only'));

			if($validate) {
				$email_hash = md5(strtolower(trim($this->request->data['User']['email'])));
				$photo = "http://www.gravatar.com/avatar/{$email_hash}";

				$data = array(
					'User' => array(
						'hash_id' => md5(date('YmdHis').Configure::read('Security.salt').rand(1,100).microtime()),
						'email' => $this->request->data['User']['email'],
						'password' => Security::hash($this->request->data['User']['password'], 'blowfish'),
						'ip' => $this->Geo->getIp(),
						'role' => 'user',
						'status' => 0
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

				if($this->User->saveAssociated($data)) {
					unset($data['User']['password']);
					$data['User']['id'] = $this->User->id;
					$data['Info']['id'] = $this->User->Info->id;
					if($this->Auth->login($data)) {
						$this->redirect($this->_getCurrentBackUrl());
					}
				}
			}
		}

		$this->set(array(
			"page_title" => __d("sweet_forum", "Sign Up"),
			"light_header" => true,
			"hide_sign_modals" => true,
		));
	}

	function signout() {
		$this->autoRender = false;

		$this->Auth->logout();
		$this->redirect($this->Auth->logoutRedirect);
	}

	function password() {
		if($this->request->is('post')) {
			$email = trim($this->request->data['User']['email']);
			if(empty($email)) throw new NotFoundException();

			$this->User->bindResendPassword();
			$find = $this->User->find('first',
				array(
					'conditions' => array('User.email' => $email),
					'contain' => array('ResendPassword')
				)
			);

			if(empty($find)) {
				$this->Session->setFlash(__d("sweet_forum", "We couldn't find you using the information you entered. Please try again."), 'default', array('class' => 'alert alert-danger margin-top15'));
			} else {
				$this->SweetMailSender = $this->Components->load('SweetForum.SweetMailSender');

				$hash = md5(date('YmdHis').rand(1,100).Configure::read('Security.salt').microtime());

				$data = array(
					'ResendPassword' => array(
						'user_id' => $find['User']['id'],
						'hash_id' => $hash
					)
				);
				if($this->User->ResendPassword->save($data, false)) {
					$settings = array(
						'theme' => 'First',
						'view' => 'SweetForum.resend_password',
						'subject' => __d("sweet_forum", "Resend password"),
						'from_email' => 'noreply@'.$_SERVER['SERVER_NAME'],
						'from_name' => 'SweetForum',
						'data' => array(
							'hash' => $hash,							
						)
					);

					if($this->SweetMailSender->send($email, $settings)) {
						$this->Session->setFlash(__d("sweet_forum", "We've sent password reset instructions to your email address."), 'default', array('class' => 'alert alert-success'));
						$hide_form = true;
					}
				} else {
					$this->Session->setFlash(__d("sweet_forum", "Error. Something was wrong. Try again later."), 'default', array('class' => 'alert alert-danger margin-top15'));
				}
			}
		}

		$this->set(array(
			"page_title" => __d("sweet_forum", "Forgot your password?"),
			"light_header" => true,
			"hide_form" => isset($hide_form) ? $hide_form : false,
			"hide_sign_modals" => true
		));
	}

	/*
	*	Password reset page
	*/
	function password_reset($hash = false) {
		if($hash === false) throw new NotFoundException();
		$hash = trim($hash);
		if(empty($hash)) throw new NotFoundException();

		$this->User->bindResendPassword();

		$find = $this->User->ResendPassword->findByHashId($hash);
		if(empty($find)) throw new NotImplementedException();

		if($this->request->is('post')) {
			$password = trim($this->request->data['User']['password']);
			if(empty($password) || mb_strlen($password) < 2) {
				$this->Session->setFlash(__d("sweet_forum", "Password is incorrect"), 'default', array('class' => 'alert alert-danger margin-top15'));
			} else {
				$data = array('User' => array('password' => Security::hash($password, 'blowfish')));

				$this->User->id = $find['ResendPassword']['user_id'];
				if($this->User->save($data, false)) {
					$this->User->ResendPassword->id = $find['ResendPassword']['id'];
					$this->User->ResendPassword->delete();

					$data = $this->User->findById($this->User->id);
					unset($data['User']['password']);
					$this->Auth->login($data);
					$this->redirect(SWEET_FORUM_BASE_URL);
				} else {
					$this->Session->setFlash(__d("sweet_forum", "Something was wrong. Password not stored"), 'default', array('class' => 'alert alert-danger margin-top15'));
				}
			}
		}

		$this->set(array(
			"page_title" => __d("sweet_forum", "Password reset"),
			"light_header" => true,
			"hide_sign_modals" => true,
			"hide_form" => isset($hide_form) ? $hide_form : false,
		));
	}

	function edit() {
		if($this->request->is('post')) {
			$this->User->Info->id = $this->Auth->user('Info.id');
			if($this->User->Info->save($this->request->data, true, array('name', 'username'))) {
				$this->_updateUserSession($this->request->data);
				$this->Session->setFlash(__d("sweet_forum", "Information was updated"), 'default', array('class' => 'alert alert-success margin-top15'));
				$this->redirect('/users/edit');
			} else {
				$this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
			}
		}

		$this->set(array(
			'page_title' => __d("sweet_forum", "Profile information")
		));
	}

	function privacy() {
		$user = $this->User->find('first',
			array(
				'conditions' => array('User.id' => $this->Auth->user('User.id')),
				'contain' => array('Privacy')
			)
		);
		if(empty($user)) throw new NotFoundException(4);

		if($this->request->is('post')) {
			$this->User->Privacy->id = $user['Privacy']['id'];
			if($this->User->Privacy->save($this->request->data, array('show_social_link'))) {
				$this->Session->setFlash('Данные обновлены', 'default', array('class' => 'green'));
				$this->redirect('/users/privacy');
			} else {
				$this->Session->setFlash('Ошибка. Данные не обновлены', 'default', array('class' => 'red'));
			}
		}

		$this->set(array(
			'user' => $user,
			'page_title' => 'Настройки приватности',
		));
	}

	function settings() {
		$this->User->contain(array('Privacy', 'Notification'));
		$user = $this->User->findById($this->Auth->user('User.id'));

		$this->set(array(
			'user' => $user,
			'page_title' => __d("sweet_forum", "Your profile settings"),
		));
	}

	function profile() {
		// check is password fields complete
		$password = $this->User->findById($this->Auth->user('User.id'), array('password'));
		if($password['User']['password'] == '0') $this->set(array("need_to_add_password" => true));

		$this->loadModel('SweetForum.Topic');
		$this->paginate = array(
			'fields' => array('Topic.name', 'Topic.url', 'Topic.created'),
			'conditions' => array('Topic.user_id' => $this->Auth->user('User.id'), 'Topic.status' => 0),
			'contain' => false,
			'limit' => 10,
			'order' => 'Topic.id DESC',
			'cache_options' => array('duration' => '1_min')
		);
		$topics = $this->paginate('Topic');

		$this->loadModel('SweetForum.Comment');
		$this->Comment->bindTopic();
		$this->paginate = array(
			'fields' => array('Comment.hash_id', 'Comment.created', 'Topic.url', 'Topic.name', 'Topic.status'),
			'conditions' => array('Comment.user_id' => $this->Auth->user('User.id'), 'Comment.status' => 0),
			'contain' => array('Topic'),
			'limit' => 10,
			'order' => 'Comment.id DESC',
			'cache_options' => array('duration' => '1_min')
		);
		$comments = $this->paginate('Comment');

		$this->set(array(
			'page_title' => __d("sweet_forum", "Your profile"),
			'topics' => $topics,
			'comments' => $comments
		));
	}

	function notifications() {
		App::uses('PrettyTime', 'SweetForum.View/Helper');
		$this->loadModel('SweetForum.Topic');

		$current_type = array_key_exists('type', $this->request->query) ? $this->request->query['type'] : 'topics';

		switch($current_type) {
			case 'topics' :
				$this->loadModel('SweetForum.TopicLikesNotification');
				$this->loadModel('SweetForum.Comment');

				$topic_likes = $this->TopicLikesNotification->find('all',
					array(
						'conditions' => array('TopicLikesNotification.to_user_id' => $this->Auth->user('User.id')),
						'limit' => 25,
						'order' => 'TopicLikesNotification.created DESC'
					)
				);

				$user_topic_comments = $this->User->query("
					SELECT Comment.hash_id, Comment.created, Topic.url, Topic.name, UserInfo.name, UserInfo.username FROM ".$this->Comment->table." AS Comment
					LEFT JOIN ".$this->User->Info->table." AS UserInfo ON UserInfo.user_id = Comment.user_id
					LEFT JOIN ".$this->Topic->table." AS Topic ON Topic.id = Comment.topic_id
					WHERE Comment.topic_id IN (
						SELECT id FROM ".$this->Topic->table." AS Topic WHERE Topic.user_id = ".$this->Auth->user('User.id')."
					)
					AND Comment.user_id != ".$this->Auth->user('User.id')."
					AND Comment.status >= 0
					ORDER BY Comment.created DESC
					LIMIT 25
				");

				$this->set(array(
					'left' => $user_topic_comments,
					'right' => $topic_likes
				));
			break;
			case 'comments' :
				$this->loadModel('SweetForum.CommentLikesNotification');
				$this->loadModel('SweetForum.Comment');

				$this->Comment->bindTopic();

				$answers_to_user = $this->Comment->find('all',
					array(
						'conditions' => array('Comment.answer_to' => $this->Auth->user('User.id')),
						'contain' => array(
							'Topic' => array(
								'fields' => array('Topic.name', 'Topic.url')
							),
							'Creator' => array(
								'fields' => array('Creator.id'),
								'Info' => array(
									'fields' => array('Info.name', 'Info.username')
								)
							)
						),
						'limit' => 25
					)
				);

				$comment_likes = $this->User->query("
					SELECT CommentLikesNotification.created, CommentLikesNotification.comment_hash_id, CommentLikesNotification.from_user_name, CommentLikesNotification.from_user_username, Topic.name, Topic.url FROM ".$this->CommentLikesNotification->table." AS CommentLikesNotification
					LEFT JOIN ".$this->Topic->table." AS Topic ON Topic.id = CommentLikesNotification.topic_id
					WHERE CommentLikesNotification.to_user_id = ".$this->Auth->user('User.id')."
					ORDER BY CommentLikesNotification.created DESC
					LIMIT 25
				");

				$this->set(array(
					'left' => $answers_to_user,
					'right' => $comment_likes
				));
			break;
		}

		$this->set(array(
			'page_title' => __d("sweet_forum", "Your notifications"),
			'current_type' => $current_type
		));
	}

	/**
	 *	/u/...
	 */
	function view($username = false) {
		if($username === false) throw new NotFoundException();

		$user = $this->User->find('first',
			array(
				'conditions' => array('Info.username' => $username),
				'contain' => array('Info', 'Privacy'),
				'cache_options' => array('duration' => 'default')
			)
		);
		if(empty($user)) throw new NotFoundException();
		
		$this->loadModel('SweetForum.Topic');
		$this->loadModel('SweetForum.Comment');
		
		$topics = $this->Topic->find('count',
			array(
				'conditions' => array('Topic.user_id' => $user['User']['id']),
				'cache_options' => array('name' => 'user_'.$user['User']['id'].'_topics_count')
			)
		);
		
		$comments = $this->Comment->find('count',
			array(
				'conditions' => array('Comment.user_id' => $user['User']['id']),
				'cache_options' => array('name' => 'user_'.$user['User']['id'].'_comment_count')
			)
		);

		$this->set(array(
			'page_title' => $user['Info']['name'],
			'user' => $user,
			'topics' => $topics,
			'comments' => $comments,
		));
	}

	/**
	 *	Report user
	 */
	function report($user_hash = false) {
		if($this->request->is('ajax')) $this->autoRender = false;

		if($user_hash === false) throw new NotFoundException();

		$user = $this->User->find('first',
			array(
				'conditions' => array('User.hash_id' => $user_hash, 'User.id !=' => $this->Auth->user('User.id')),
			)
		);
		if(empty($user)) throw new NotFoundException();

		$this->loadModel('SweetForum.UserReport');

		if($this->request->is('post')) {
			// check if user already sent the report
			$check = $this->UserReport->findByUserIdAndByUserId($user['User']['id'], $this->Auth->user('User.id'));
			if(!empty($check)) {
				$message = __d('sweet_forum', 'You already sent report about this user');

				if($this->request->is('ajax')) {
					return json_encode(array(
						'success' => false,
						'message' => $message
					));
				} else {
					$this->Session->setFlash($message, 'default', array('class' => 'alert alert-danger margin-top15'));
				}
			}

			$data = array(
				'UserReport' => array(
					'user_id' => $user['User']['id'],
					'by_user_id' => $this->Auth->user('User.id'),
					'report_message' => $this->request->data['UserReport']['report_message']
				)
			);

			if($this->UserReport->save($data, false)) {
				$message = __d('sweet_forum', 'Report was successfully sent');

				if($this->request->is('ajax')) {
					return json_encode(array(
						'success' => true,
						'message' => $message
					));
				} else {
					$this->Session->setFlash($message, 'default', array('class' => 'alert alert-success margin-top15'));
				}
			} else {
				$message = __d('sweet_forum', 'Error. Report was not sent');

				if($this->request->is('ajax')) {
					return json_encode(array(
						'success' => false,
						'message' => $message
					));
				} else {
					$this->Session->setFlash($message, 'default', array('class' => 'alert alert-danger margin-top15'));
				}
			}
		}
	}

	function ban() {
		$this->set(array(
			'page_title' => 'Аяяй, вы забаненеы'
		));
	}
	/*
	*	Set password form profile
	*/
	function update_password() {
		if($this->request->is('post')) {
			$this->autoRender = false;

			$password = trim($this->request->data['User']['password']);
			$error = true; $message = "";
			if(empty($password)) {
				$message = __d("sweet_forum", "Password value is incorrect");
			} else if (mb_strlen($password) < 3) {
				$message = __d("sweet_forum", "Password value is too short");
			} else {
				$data = array('User' => array('password' => Security::hash($password, 'blowfish')));

				$this->User->id = $this->Auth->user('User.id');
				if($this->User->save($data, false)) {
					$error = false;
					$message = __d("sweet_forum", "Thank you, password is stored");
				} else {
					$message = __d("sweet_forum", "Something was wrong. Password not stored");
				}
			}
			echo json_encode(array('error' => $error, 'message' => $message));
		} else {
			throw new NotFoundException();
		}
	}
}