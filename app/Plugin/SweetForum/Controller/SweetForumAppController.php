<?php
App::uses('Controller', 'Controller');
class SweetForumAppController extends AppController {	
	const THEME = "First"; // треба буде зробити, щоб можна було змінювати динамічно

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
            ),
            'logoutRedirect' => SWEET_FORUM_BASE_URL,
            'loginRedirect' => SWEET_FORUM_BASE_URL,
        ),
        'Session'
    );

    function beforeFilter() {
		$this->theme = 'First';

        $this->_checkForBan();

        $params = http_build_query($_GET);
        $url = Router::url(null, true);
        if(!empty($params)) $url .= "?{$params}";

		$this->loadModel('SweetForum.Setting');
		$forum_settings = $this->Setting->find('first');		
		
		if(!(bool) $forum_settings['Setting']['is_installed']) {
			if($this->request->here != SWEET_FORUM_BASE_URL.'mc/css' && $this->request->here != SWEET_FORUM_BASE_URL.'mc/js') {
				if($this->request->here != SWEET_FORUM_BASE_URL.'install') $this->redirect(SWEET_FORUM_BASE_URL.'install');
			}
		}
		
		Configure::write('Config.language', $forum_settings['Setting']['lang']);

        $this->set(array(
            'logged_in' => $this->Auth->loggedIn(),
            'user_data' => $this->Auth->user(),
            'is_admin' => $this->Auth->user('User.role') == 'admin',
            'current_url' => urlencode($url),
			'sf_forum_lang' => Configure::read("Config.language"),
			'sweet_forum_base_url' => SWEET_FORUM_BASE_URL
        ));
    }

    function beforeRender() {
        if($this->name == 'CakeError') $this->layout = 'error';
    }

    // проверяем на бан
    private function _checkForBan() {
        if($this->name == 'Users' && $this->action == 'ban') return true;
        if($this->name == 'Users' && $this->action == 'signout') return true;

        //if($this->Auth->user('User.status') == '-1') $this->redirect('/users/ban');
    }

    /*
    *   Получаем последнюю просмотренную страницу
    */
    protected function getLastVisitedPage() {
        $c = count($this->visited_pages);

		if($c == 1) return end($this->visited_pages);
		else if($c > 1) return $this->visited_pages[($c - 1) - 1];
		else return '/';
    }
}
