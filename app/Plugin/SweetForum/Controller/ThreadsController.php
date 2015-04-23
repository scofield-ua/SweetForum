<?php
class ThreadsController extends SweetForumAppController {
    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(array('view'));
    }

    function view($url = false) {
        if($url === false) throw new NotFoundException();

        $thread_info = $this->Thread->find('first',
            array(
                'contain' => false,
                'conditions' => array('Thread.url' => $url)
            )
        );
        if(empty($thread_info)) throw new NotFoundException(4);

        $current_page = array_key_exists('page', $this->request->query) ? (int) $this->request->query['page'] : 1;

        if(array_key_exists('page', $this->request->query)) {
            if($this->request->query['page'] == 1) $this->redirect(SWEET_FORUM_BASE_URL.'threads/view/'.$url);
        }

        # подключаем нужные хелперы
        App::uses('PrettyTime', 'View/Helper');

        # ищем топики
        $conds = array('Topic.thread_id' => $thread_info['Thread']['id'], 'Topic.status' => 0);
        $this->Thread->Topic->recursive = 2;

        $this->paginate = array(
            'page' => $current_page,
            'conditions' => $conds,
            'limit' => 20,
            'contain' => array(
                'Creator' => array(
                    'Info' => array(
                        'fields' => array('name', 'avatar')
                    )
                ),
            ),
            'order' => 'Topic.id DESC',
            'cache_options' => array()
        );
        $topics = $this->paginate($this->Thread->Topic);

        $this->set(array(
            'page_title' => $thread_info['Thread']['name'],
            'thread_info' => $thread_info,
            'topics' => $topics,
            'current_page' => $current_page
        ));
    }
}