<?php
class CommentsController extends SweetForumAppController {
    public $uses = array('SweetForum.Comment', 'SweetForum.Topic');
    
    function beforeFilter() {
        parent::beforeFilter();
    }
    
    private function _deleteTopicCache($url) {
        Cache::delete('topic_'.md5($url.'1'), 'sf_default');
        Cache::delete('topic_'.md5($url.'2'), 'sf_default');
    }

    function add($topic_url = null) {
        $this->autoRender = false;
        
        if($topic_url === null) throw new NotFoundException();
        
        $this->Topic->recursive = -1;
        $topic = $this->Topic->findByUrl($topic_url);
        if(empty($topic)) throw new NotFoundException();
        
        $this->Comment->Info->set($this->request->data);
        if($this->Comment->Info->validates(array('fieldList' => array('text')))) {
            // check for repeat
            $check = $this->Comment->find('first',
                array(
                    'conditions' => array(
                        'AND' => array(
                            'Comment.created > timestampadd(minute, -2, now())',
                            'Comment.user_id' => $this->Auth->user('User.id'),
                            'Comment.topic_id' => $topic['Topic']['id'],
                            'Comment.status' => 0,
                            'Info.text' => trim($this->request->data['Info']['text'])
                        )
                    ),
                    'fields' => array('Comment.id'),
                    'contain' => array('Info')
                )
            );
            if(!empty($check)) {
                $this->Session->setFlash(__d("sweet_forum", "You already add such comment"), 'default', array('class' => 'alert alert-danger margin-top15'));
            } else {
                $data = array(
                    'Comment' => array(
                        'hash_id' => md5(date('Ymdhis').microtime().AuthComponent::user('User.id').$f['Topic']['id']),
                        'user_id' => $this->Auth->user('User.id'),
                        'topic_id' => $topic['Topic']['id'],
                    ),
                    'Info' => array(
                        'text' => $this->request->data['Info']['text']
                    )
                );
                
                if($this->request->data('Comment.answer_to') !== null) {
                    $val = trim($this->request->data('Comment.answer_to'));
                    if(!empty($val)) {
                        // check comment
                        $this->Comment->recursive = -1;
                        $find = $this->Comment->findByHashIdAndAnswerTo($val, null, array('id', 'topic_id'));
                        if(!empty($find)) {
                            if($find['Comment']['topic_id'] == $f['Topic']['id']) $data['Comment']['answer_to'] = $find['Comment']['id'];
                        }
                    }
                }
    
                if($this->Comment->saveAssociated($data, array('validate' => true))) {
                    $comment_id = $this->Comment->id;
                    
                    $this->_deleteTopicCache($topic['Topic']['url']);
                    $this->Session->setFlash(__d("sweet_forum", "Comment was added"), 'default', array('class' => 'alert alert-success margin-top15'));
                    
                    App::uses('MailMessagesController', 'SweetForum.Controller');
                    $mm = new MailMessagesController();
                    $mm->send($topic['Topic']['user_id'], 1, array('topic_id' => $topic['Topic']['id']));
                } else {
                    $this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
                }
            }
        } else {
            $this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
        }
        
        $url = isset($comment_id) ? SWEET_FORUM_BASE_URL.'topic/'.$topic['Topic']['url'].'#c-'.$comment_id : SWEET_FORUM_BASE_URL.'topic/'.$topic['Topic']['url'].'#flashMessage';        
        $this->redirect($url);
        
        return false;
    }

    function edit($hash_id = null) {
        if($hash_id === null) throw new NotFoundException();

        // check comment
        $f = $this->Comment->find('first',
            array(
                'conditions' => array('Comment.hash_id' => $hash_id, 'Comment.user_id' => $this->Auth->user('User.id')),
                'contain' => array('Info'),
                'fields' => array('Comment.id', 'Comment.topic_id', 'Info.text', 'Info.id')
            )
        );
        if(empty($f)) throw new NotFoundException();

        $this->loadModel('SweetForum.Topic');
        $topic = $this->Topic->find('first',
            array(
                'conditions' => array('Topic.id' => $f['Comment']['topic_id']),
                'fields' => array('name', 'url'),
                'contain' => false,
                'cache_options' => array('duration' => '1_min')
            )
        );
        if(empty($topic)) throw new NotFoundException();

        if($this->request->is('post')) {
            if(array_key_exists('delete', $this->request->data)) { // delete
                $this->Comment->id = $f['Comment']['id'];
                if($this->Comment->save(array('status' => -1), false)) {
                    $this->_deleteTopicCache($topic['Topic']['url']);
                    $this->redirect(SWEET_FORUM_BASE_URL.'topic/'.$topic['Topic']['url']);
                } else {
                    $this->Session->setFlash(__d("sweet_forum", "Error. Comment not deleted"), 'default', array('class' => 'alert alert-error margin-top15'));
                }
            } else { // update
                $this->Comment->id = $f['Comment']['id'];
                $this->Comment->Info->id = $f['Info']['id'];
                $this->request->data['Info']['is_modified'] = 1;
                if($this->Comment->Info->save($this->request->data['Info'], true, array('text', 'is_modified')) && $this->Comment->save(array('modified' => date('Y-m-d H:i:s')), false)) {
                    $this->_deleteTopicCache($topic['Topic']['url']);
                    $this->redirect(SWEET_FORUM_BASE_URL.'topic/'.$topic['Topic']['url'].'#c-'.$hash_id);
                } else {
                    $this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-error margin-top15'));                    
                }
            }
        }

        $this->set(array(
            'find' => $f,
            'topic' => $topic
        ));
    }
}
