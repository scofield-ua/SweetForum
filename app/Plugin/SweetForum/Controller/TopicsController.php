<?php
App::uses('Sanitize', 'Utility');
class TopicsController extends SweetForumAppController {
    public $components = array('SweetForum.Translate');

    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(array('view', 'load_iframe_data'));
    }

    private function _checkUrlName($name) {
        if($name === false) throw new NotFoundException(4);

        // проверяем ссылку топика
        $this->Topic->recursive = -1;
        $f = $this->Topic->findByUrl($name, array('id'));
        if(empty($f)) throw new NotFoundException(4);
    }
    
    /*
    *   Save topic data to session for preview page
    */
    private function _makePreviewSession($data) {
        $previews = $this->Session->check('TopicPreview.count') ? $this->Session->read('TopicPreview.count') : 0;
        $n = $previews + 1;

        $this->Session->write("TopicPreview{$n}.name", h($this->request->data['Topic']['name']));
        $this->Session->write("TopicPreview{$n}.text", $this->request->data['Topic']['text']);        
        $this->Session->write('TopicPreview.count', $n);
    }
    
    /*
    *   @param array $data - нужно передать $this->request->data
    *   @param bool $just_return - если true, то просто будет возвращено значение $type
    *   @warning - функцию нужно вызывать после валидации данных
    */
    private function _typeInputLogic($data, $just_return = false) {
        $type = 0;
        if(array_key_exists('Topic', $this->request->data)) {
            if(array_key_exists('type', $this->request->data['Topic'])) {
                $type = (int) $this->request->data['Topic']['type'];
                if($type < 0 || $type > 2) $type = 0;
            }
        }

        if($just_return) return $type;

        if($type == 1) {
            $previews = $this->Session->check('TopicPreview.count') ? $this->Session->read('TopicPreview.count') : 0; # количество уже созданных первьюшек
            $n = $previews + 1;

            $this->Session->write("TopicPreview{$n}.name", h($this->request->data['Topic']['name']));
            $this->Session->write("TopicPreview{$n}.text", $this->request->data['Topic']['text']);
            $this->Session->write('TopicPreview.count', $n);
        }

        return $type;
    }

    function add($thread = false) {
        if($thread === false) throw new NotFoundException();

        // check thread
        $this->loadModel('SweetForum.Thread');
        $this->Thread->recursive = -1;
        $thread = $this->Thread->findByUrl($thread);
        if(empty($thread)) throw new NotFoundException();
        
        if($this->request->is('post')) {
            if(array_key_exists('post', $this->request->data)) { // if post
                $this->request->data['Topic']['thread_id'] = $thread['Thread']['id'];
                $this->request->data['Topic']['user_id'] = $this->Auth->user('User.id');

                $url = trim(preg_replace("/[^A-Za-z0-9-]/", "", $this->Translate->toUrl($this->request->data['Topic']['name'])));
                if(!empty($url)) $this->request->data['Topic']['url'] = "{$url}-".date('d-m-y');
                else $this->request->data['Topic']['url'] = md5(date('YmdHis').microtime());

                $this->Topic->set($this->request->data);

                // check url
                if(!$this->Topic->validates(array('fieldList' => array('url')))) $this->request->data['Topic']['url'] = "{$url}-".md5(date('d-m-Y H:i:s').microtime());
                
                $this->Topic->set($this->request->data);

                if($this->Topic->save()) {
                    $this->redirect(SWEET_FORUM_BASE_URL.'topic/'.$this->request->data['Topic']['url']);
                } else {
                    $this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
                }
            } else if(array_key_exists('preview', $this->request->data)) { // if preview
                App::uses('TopicText', 'SweetForum.Lib');
                
                $this->request->data['Topic']['text'] = TopicText::processText($this->request->data['Topic']['text']);                
                $this->_makePreviewSession($this->request->data);                            
                
                $this->redirect(SWEET_FORUM_BASE_URL.'topics/preview/'.$this->Session->read('TopicPreview.count'));
            }            
        }

        $this->set(array(
            'thread' => $thread,
            "page_title" => __d("sweet_forum", "Start new topic")
        ));
    }

    function view($name = false) {
        $this->_checkUrlName($name);
        
        $comment_sort_type = array_key_exists('comments', $this->request->query) ? (int) $this->request->query['comments'] : 1;
        if($comment_sort_type > 2) $comment_sort_type = 2;

        $c_n = "topic_".md5($name.$comment_sort_type);
        $c_d = "sf_default";
        if(!$f = Cache::read($c_n, $c_d)) {
            $comment_sort_string = "Comment.created ASC";
            switch($comment_sort_type) {
                case 1 :
                    $comment_sort_string = "Comment.created ASC";
                break;
                case 2 :
                    $comment_sort_string = "Comment.created DESC";
                break;
            }
            
            $f = $this->Topic->find('first',
                array(					
                    'conditions' => array('Topic.url' => $name, 'Topic.status >=' => 0),
                    'contain' => array(
                        'Thread' => array(
                            'fields' => array('Thread.id', 'Thread.name', 'Thread.url')
                        ),
                        'Creator' => array(
                            'fields' => array('Creator.id'),
                            'Info' => array(
                                'fields' => array('Info.username', 'Info.name', 'Info.avatar')
                            )
                        ),
                        'Comment' => array(
                            'conditions' => array('Comment.status' => 0, 'Comment.answer_to' => null),
                            'order' => $comment_sort_string,
                            'Creator' => array(
                                'fields' => array('Creator.id'),
                                'Info' => array(
                                    'fields' => array('Info.username', 'Info.name', 'Info.avatar')
                                )
                            ),
                            'Info',
                            'Answer' => array(
                                'Creator' => array(
                                    'fields' => array('Creator.id'),
                                    'Info' => array(
                                        'fields' => array('Info.username', 'Info.name', 'Info.avatar')
                                    )
                                ),
                                'Info',
                                'Like' => array('fields' => array('Like.user_id'))
                            ),                            
                            'Like' => array('fields' => array('Like.id', 'Like.user_id'))
                        ),
                        'Like' => array('fields' => array('Like.user_id'))
                    ),                    
                )
            );
            
            if(empty($f)) throw new NotFoundException();
            else Cache::write($c_n, $f, $c_d);
        }

        # подключаем нужные хелперы\библиотеки
        App::uses('PrettyTime', 'SweetForum.View/Helper');
        App::uses('TopicText', 'SweetForum.Lib');

        # проверяем, это тема пользователя, который её открыл
        $owner = false;
        if($this->Auth->loggedIn()) $owner = ($this->Auth->user('User.id') == $f['Topic']['user_id']) || ($this->Auth->user('User.role') == 'admin');

        # additional setting
        $meta_d = mb_substr($f['Topic']['text'], 0, 100)."...";
        
        # format topic text
        # options
        TopicText::$options = array(
            "cache_options" => array("duration" => "sf_default"),
            "gallery" => array(
                "class" => "venobox",
                "gallery-id" => "venobox-creator"
            ),
            "not_load_iframes" => true
        );
        $f['Topic']['text'] = TopicText::processText($f['Topic']['text']);        

        $this->set(array(
            'page_title' => $f['Topic']['name'],
            'find' => $f,
            'topic_date' => $f['Topic']['created'],            
            'owner' => $owner,
            'meta_d' => $meta_d,
            'special_min_css' => array('sweet_forum/First/css/venobox/venobox.css'),
            'special_min_js' => array('sweet_forum/First/js/topics/view.js', 'sweet_forum/First/js/likes/like.js')
        ));
    }

    function edit($name = false) {
        $this->_checkUrlName($name);

        $conds = array('AND' => array('Topic.url' => $name));
        if($this->Auth->user('User.role') != 'admin') $conds['AND']['Topic.user_id'] = $this->Auth->user('User.id');

        $find = $this->Topic->find('first',
            array(
                'conditions' => $conds,
                'contain' => array('Thread'),
                'fields' => array('Topic.id', 'Topic.thread_id', 'Topic.name', 'Topic.status', 'Topic.text', 'Thread.url')
            )
        );
        if(empty($find)) throw new NotFoundException();

        if($this->request->is('post')) {
            $this->Topic->set($this->request->data);
            
            if($this->Topic->validates(array('fieldList' => array('name', 'text')))) {
                if(array_key_exists('update', $this->request->data)) { // if update button
                    $fields = array('name', 'text', 'is_modified');
                    
                    $this->Topic->id = $find['Topic']['id'];
                    $this->request->data['Topic']['is_modified'] = 1;
                    
                    if($this->Topic->save($this->request->data, false, $fields)) {
                        Cache::delete("topic_".md5($name.'1'), "sf_default");
                        Cache::delete("topic_".md5($name.'2'), "sf_default");
                        $this->redirect(SWEET_FORUM_BASE_URL."topic/{$name}");
                    } else {
                        $this->Session->setFlash(__d("sweet_forum", "Error, topic not updated"), 'default', array('class' => 'alert alert-danger margin-top15'));
                    }
                } else if(array_key_exists('preview', $this->request->data)) { // if preview button                                    
                    App::uses('TopicText', 'SweetForum.Lib');
                    
                    TopicText::$options = array(
                        "cache_options" => array("duration" => "sf_default"),
                        "gallery" => array(
                            "class" => "venobox",
                            "gallery-id" => "venobox-creator"
                        ),
                        "not_load_iframes" => true
                    );
                    $this->request->data['Topic']['text'] = TopicText::processText($this->request->data['Topic']['text']); 
                    
                    $this->_makePreviewSession($this->request->data);                            
                
                    $this->redirect(SWEET_FORUM_BASE_URL.'topics/preview/'.$this->Session->read('TopicPreview.count'));
                } else if(array_key_exists('delete', $this->request->data)) { // if delete button
                    $this->Topic->id = $find['Topic']['id'];
                    if($this->Topic->delete()) {
                        $this->redirect(SWEET_FORUM_BASE_URL.'threads/view/'.$find['Thread']['url']);
                    } else {
                        $this->Session->setFlash(__d("sweet_forum", "Error, topic not deleted"), 'default', array('class' => 'alert alert-danger margin-top15'));                        
                    }
                }
            }
        }

        $this->set(array(
            'url' => $name,
            'find' => $find,
            'page_title' => __("Topic edit")." - ".$find['Topic']['name'],
        ));
    }

    function preview($id = false) {
        if($id === false) throw new NotFoundException();
        $id = (int) $id;
        if($id == 0) throw new NotFoundException();

        if(!$this->Session->check("TopicPreview{$id}")) throw new NotFoundException();        
        
        $this->set(array(
            'name' => $this->Session->read("TopicPreview{$id}.name"),
            'text' => $this->Session->read("TopicPreview{$id}.text"),    
            'special_min_css' => array('sweet_forum/First/css/venobox/venobox.css'),
            'special_min_js' => array('sweet_forum/First/js/topics/view.js')
        ));
    }
    
    /**
     *  Function to load iframe data (such as youtube, vimeo, soundcloud, etc)
     */
    function load_iframe_data() {
        $this->autoRender = false;
        
        # url to resource
        $url = array_key_exists('url', $this->request->query) ? $this->request->query['url'] : "";
        # type of resource
        $type = array_key_exists('type', $this->request->query) ? $this->request->query['type'] : "";
        
        if(empty($url) || empty($type)) throw new NotFoundException();
        
        App::uses('TopicText', 'SweetForum.Lib');
        
        $return = array('html' => '<a href="'.$url.'" target="_blank">'.$url.'</a>');
        $data = array();        
        switch($type) {
            case 'video' : $data = TopicText::getVideo($url); break;
            case 'audio' : $data = TopicText::getAudio($url); break;
            case 'social-quote' : $data = TopicText::getSocialQuote($url); break;
        }
        
        if(array_key_exists('html', $data)) $return['html'] = $data['html'];
        
        return json_encode($return);
    }
}
