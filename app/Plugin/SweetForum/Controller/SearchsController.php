<?php
class SearchsController extends SweetForumAppController {
    public $uses = array();
    public $components = array('SweetForum.SweetSearch');

    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(array('index'));
    }

    function index() {
        $fields = array('s', 'thread');

        foreach($fields as $var) if(array_key_exists($var, $this->request->query)) $$var = trim($this->request->query[$var]);

        # check
        if(!isset($s)) throw new NotFoundException();
        if(empty($s)) throw new NotFoundException();
        if(mb_strlen($s) < 2) throw new NotFoundException();

        $conds['AND'] = $this->SweetSearch->makeConditionsArray($s, "Topic.name");

        # additional params
        if(isset($thread)) {
            $this->loadModel('SweetForum.Thread');

            $this->Thread->secure = array();
            $this->Thread->recursive = -1;
            $f = $this->Thread->findByUrl($thread, array('id', 'name', 'url'));
            if(!empty($f)) $conds['AND']['Topic.thread_id'] = $f['Thread']['id'];

            $this->set(array(
                'thread' => $f
            ));
        }

        $this->loadModel('SweetForum.Topic');

        $this->paginate = array(
            'recursive' => 2,
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
            'cache_options' => array('duration' => '1_min')
        );

        $find = $this->paginate('Topic');

        $this->set(array(
            'topics' => $find,
            'page_title' => __d("sweet_forum", "Search").": &laquo;".h($s)."&raquo;",
            's' => $s
        ));
    }
}
