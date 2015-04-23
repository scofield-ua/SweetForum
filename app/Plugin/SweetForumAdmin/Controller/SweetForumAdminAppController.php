<?php
//App::uses('Controller', 'Controller');
class SweetForumAdminAppController extends AppController {
    public $components = array(
		'Acl',
        'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'email', 'password' => 'password'),
					'passwordHasher' => 'Blowfish',
				),
			),
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'signin',
				'plugin' => 'sweet_forum'
            ),
            'logoutRedirect' => '/',
            'loginRedirect' => '/',
        ),
        'Session'
    );

    function beforeFilter() {
		$this->loadModel('SweetForumAdmin.Setting');
		$forum_settings = $this->Setting->find('first');
		
		Configure::write('Config.language', $forum_settings['Setting']['lang']);
		
        $this->set(array(
            'logged_in' => $this->Auth->loggedIn(),
            'user_data' => $this->Auth->user(),
            'is_admin' => $this->Auth->user('User.role') == 'admin',
			'sf_forum_lang' => Configure::read("Config.language"),
			'sweet_forum_base_url' => SWEET_FORUM_BASE_URL
        ));
    }
}
