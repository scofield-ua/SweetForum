<?php
class LikesController extends SweetForumAppController {
    public $uses = array('SweetForum.Like', 'SweetForum.Topic', 'SweetForum.Comment');
    
    function beforeFilter() {
        parent::beforeFilter();
        
        $this->Auth->allow(array('get'));
    }
    
    /**
     *  Likes contoller method
     *  
     */
    function add() {
        if($this->request->is('ajax')) {
            $this->autoRender = false;
            // проверяем переданные данные
            if(array_key_exists('type', $this->request->data) && array_key_exists('for', $this->request->data)) {
                $type = (int) $this->request->data['type'];
                $for = (string) trim($this->request->data['for']);

                if($type < 0 || $type > 1) throw new NotFoundException();
                if(empty($for)) throw new NotFoundException();
                if(strlen($for) > 250) throw new NotFoundException();
                
                if($type == 0) { // like for topic
                    $topic = $this->Topic->find('first',
                        array(
                            'conditions' => array('Topic.url' => $for),
                            'fields' => array('Topic.id', 'Topic.url'),
                            'contain' => false,
                            'cache_options' => array('1_min')
                        )
                    );
                    if(empty($topic)) throw new NotFoundException();
                    
                    $type_id = $topic['Topic']['id'];
                } else if($type == 1){ // like for comment                    
                    $comment = $this->Comment->find('first',
                        array(
                            'conditions' => array('Comment.hash_id' => $for),
                            'fields' => array('Comment.id', 'Comment.topic_id'),
                            'contain' => false,
                            'cache_options' => array('1_min')
                        )
                    );
                    if(empty($comment)) throw new NotFoundException();
                    
                    $type_id = $comment['Comment']['id'];

                    $topic = $this->Topic->find('first',
                        array(
                            'conditions' => array('Topic.id' => $comment['Comment']['topic_id']),
                            'fields' => array('Topic.url'),
                            'contain' => false,
                            'cache_options' => array('1_min')
                        )
                    );
                    if(empty($topic)) throw new NotFoundException();
                }

                // check, maybe user alredy voted
                $conds = array(
                    'user_id' => $this->Auth->user('User.id'),
                    'type_id' => $type_id,
                    'type' => $type
                );

                $check = $this->Like->find('first',
                    array(
                        'conditions' => $conds,
                        'contain' => false,
                        'fields' => array('Like.id')
                    )
                );
                // if voted than delete vote
                if(!empty($check)) {
                    $this->Like->id = $check['Like']['id'];
                    if($this->Like->delete()) {
                        // delete topic cache
                        Cache::delete("topic_".md5($topic['Topic']['url']), "sf_default");
                        
                        return json_encode(
                            array(
                                'type' => 'delete',
                                'success' => true,
                                'username' => $this->Auth->user('Info.username')
                            )
                        );
                    } else {
                        throw new NotFoundException();
                    }
                } else {
                    // make data for store
                    $data = array(
                        'Like' => array(
                            'user_id' => $this->Auth->user('User.id'),
                            'type_id' => $type_id,
                            'type' => $type
                        )
                    );
                    $this->Like->create();
                    if($this->Like->save($data)) {
                        // delete topic cache
                        Cache::delete("topic_".md5($topic['Topic']['url'].'1'), "sf_default");
                        Cache::delete("topic_".md5($topic['Topic']['url'].'2'), "sf_default");

                        // make html code for "likers" list
                        App::uses('HtmlHelper', 'View/Helper');
                        $html_helper = new HtmlHelper(new View());
                        $ava = $html_helper->image($this->Auth->user('Info.avatar')."?s=20", array('align' => 'left', 'class' => 'margin-right5'));                        
                        $link_text = $ava."<small>".$this->Auth->user('Info.name')."</small>";
                        
                        $html = "
                            <li class='text-left overflow-hide' data-user='".$this->Auth->user('Info.username')."'>
                                ".$html_helper->link($link_text, SWEET_FORUM_BASE_URL.'u/'.$this->Auth->user('Info.username'), array('title' => $this->Auth->user('Info.name'), 'escape' => false))."
                            </li>
                        ";

                        return json_encode(
                            array(
                                'type' => 'add',
                                'success' => true,
                                'html' => $html
                            )
                        );
                    } else {
                        throw new NotFoundException();
                    }
                }
            }
        }
        throw new NotFoundException();
    }
    
    /**
     *  Function to get list of users that liked topic or comment
     *  @param string $type Must be or "topic" or "comment"
     *  @return json Users list
     */
    function get($type = false) {
        $this->autoRender = false;
        
        $id = (string) trim($this->request->query['id']); // topic url or hash_id for comment        
        if(empty($id) || $type === false) throw new NotFoundException();
        
        switch($type) {
            case 'topic' :
                $topic = $this->Topic->find('first',
                    array(
                        'conditions' => array('Topic.url' => $id),
                        'fields' => array('Topic.id'),
                        'contain' => false,
                        'cache_options' => array('name' => 'likes_get_topic_'.md5($id), 'duration' => 'sf_1_day')
                    )
                );
                
                if(empty($topic)) throw new NotFoundException();
                
                $c_n = 'likes_get_topic_likes_'.md5($id);
                $c_d = 'sf_1_min';
                if(!$likes = Cache::read($c_n, $c_d)) {
                    $likes = $this->Like->query("
                        SELECT L.hash_id, L.created, U.name, U.username, U.avatar FROM ".$this->Like->table." AS L
                        LEFT JOIN ".$this->Like->Owner->Info->table." AS U ON U.user_id = L.user_id
                        WHERE L.type_id = ".$topic['Topic']['id']." AND L.type = 0
                        ORDER BY L.created DESC
                    ");
                    
                    Cache::write($c_n, $likes, $c_d);
                }
                
                echo json_encode($likes);
            break;
            case 'comment' :
                $comment = $this->Comment->find('first',
                    array(
                        'conditions' => array('Comment.hash_id' => $id),
                        'fields' => array('Comment.id'),
                        'contain' => false,
                        'cache_options' => array('name' => 'likes_get_comment_'.md5($id), 'duration' => 'sf_1_day')
                    )
                );
                
                if(empty($comment)) throw new NotFoundException();
                
                $c_n = 'likes_get_comment_likes_'.md5($id);
                $c_d = 'sf_1_min';
                if(!$likes = Cache::read($c_n, $c_d)) {
                    $likes = $this->Like->query("
                        SELECT L.hash_id, L.created, U.name, U.username, U.avatar FROM ".$this->Like->table." AS L
                        LEFT JOIN ".$this->Like->Owner->Info->table." AS U ON U.user_id = L.user_id
                        WHERE L.type_id = ".$comment['Comment']['id']." AND L.type = 1
                        ORDER BY L.created DESC
                    ");
                    
                    Cache::write($c_n, $likes, $c_d);
                }
                
                echo json_encode($likes);
            break;
            default :
                throw new NotFoundException();
            break;
        }
    }
}
