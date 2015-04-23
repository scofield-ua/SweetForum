<?php
class MessagesController extends SweetForumAppController {
    const MESSAGE_PER_PAGE = 50;
    
    public $uses = array('SweetForum.Message', 'SweetForum.MessageArchived', 'SweetForum.MessageUserBlock');
    
    // inbox conversations
    function index() {
        $conversations = $this->Message->query("
            SELECT Message.id, Message.created, Message.checked, Message.from_user_id, max(Message.id) as max_id, Info.text, Creator.hash_id, CreatorInfo.name, CreatorInfo.avatar, LastMessage.*, Archived.user_id FROM ".$this->Message->table." AS Message
            LEFT JOIN ".$this->Message->Info->table." AS Info ON Info.message_id = Message.id
            LEFT JOIN ".$this->Message->Creator->table." AS Creator ON Creator.id = Message.from_user_id
            LEFT JOIN ".$this->Message->Creator->Info->table." AS CreatorInfo ON CreatorInfo.user_id = Message.from_user_id
            LEFT JOIN ".$this->MessageArchived->table." AS Archived ON Archived.user_id = Message.from_user_id
            LEFT JOIN ".$this->MessageUserBlock->table." AS Blocked ON Blocked.user_id = Message.from_user_id
            INNER JOIN (
                SELECT M.created, M.from_user_id, M.to_user_id, M.checked, I.text FROM ".$this->Message->table." AS M
                LEFT JOIN ".$this->Message->Info->table." AS I ON I.message_id = M.id                
                ORDER BY M.created DESC                
            ) LastMessage ON (LastMessage.from_user_id = Message.from_user_id AND Message.to_user_id = ".$this->Auth->user('User.id').") OR (LastMessage.from_user_id = ".$this->Auth->user('User.id')." AND LastMessage.to_user_id = Message.from_user_id)
            WHERE Message.to_user_id = ".$this->Auth->user('User.id')." AND Message.status = 0 AND Archived.user_id IS NULL AND Blocked.user_id IS NULL
            GROUP BY Message.from_user_id
            ORDER BY max_id DESC
            LIMIT 100
        ");
        
        $this->set(array(
            'page_title' => __d("sweet_forum", "Inbox"),
            'current_page' => __d("sweet_forum", "Inbox"),
            'conversations' => $conversations
        ));
    }
    
    // archived conversations
    function archived() {
        $conversations = $this->Message->query("
            SELECT Message.id, Message.created, Message.checked, Message.from_user_id, max(Message.id) as max_id, Info.text, Creator.hash_id, CreatorInfo.name, CreatorInfo.avatar, LastMessage.*, Archived.user_id FROM ".$this->Message->table." AS Message
            LEFT JOIN ".$this->Message->Info->table." AS Info ON Info.message_id = Message.id
            LEFT JOIN ".$this->Message->Creator->table." AS Creator ON Creator.id = Message.from_user_id
            LEFT JOIN ".$this->Message->Creator->Info->table." AS CreatorInfo ON CreatorInfo.user_id = Message.from_user_id
            LEFT JOIN ".$this->MessageArchived->table." AS Archived ON Archived.user_id = Message.from_user_id AND Archived.by_user_id = ".$this->Auth->user('User.id')."
            INNER JOIN (
                SELECT M.created, M.from_user_id, M.to_user_id, M.checked, I.text FROM ".$this->Message->table." AS M
                LEFT JOIN ".$this->Message->Info->table." AS I ON I.message_id = M.id                
                ORDER BY M.created DESC                
            ) LastMessage ON (LastMessage.from_user_id = Message.from_user_id AND Message.to_user_id = ".$this->Auth->user('User.id').") OR (LastMessage.from_user_id = ".$this->Auth->user('User.id')." AND LastMessage.to_user_id = Message.from_user_id)
            WHERE Message.to_user_id = ".$this->Auth->user('User.id')." AND Message.status = 0 AND Archived.user_id IS NOT NULL
            GROUP BY Message.from_user_id
            ORDER BY max_id DESC
            LIMIT 100
        ");
        
        $this->set(array(
            'page_title' => __d("sweet_forum", "Archived"),
            'current_page' => __d("sweet_forum", "Archived"),
            'conversations' => $conversations
        ));
    }
    
    // other conversations (started by you)
    function other() {
        $conversations = $this->Message->query("
            SELECT Message.id, Message.created, Message.checked, Message.from_user_id, Message.to_user_id, max(Message.id) as max_id, ToUser.hash_id, ToUserInfo.name, ToUserInfo.avatar FROM ".$this->Message->table." AS Message
            LEFT JOIN ".$this->Message->Creator->table." AS ToUser ON ToUser.id = Message.to_user_id
            LEFT JOIN ".$this->Message->Creator->Info->table." AS ToUserInfo ON ToUserInfo.user_id = Message.to_user_id            
            WHERE Message.from_user_id = ".$this->Auth->user('User.id')." AND Message.status = 0
            GROUP BY Message.to_user_id
            ORDER BY max_id DESC
            LIMIT 100
        ");        
        
        $this->set(array(
            'page_title' => __d("sweet_forum", "Other"),
            'current_page' => __d("sweet_forum", "Other"),
            'conversations' => $conversations
        ));
    }
    
    // blocked users
    function blocked() {
        $this->paginate = array(
            'conditions' => array('MessageUserBlock.by_user_id' => $this->Auth->user('User.id')),
            'contain' => array(
                'User' => array(
                    'fields' => array('User.id', 'User.hash_id'),
                    'Info' => array(
                        'fields' => array('Info.username', 'Info.name', 'Info.avatar')
                    )
                )
            ),
            'limit' => 100
        );
        
        $users = $this->paginate('MessageUserBlock');
        
        $this->set(array(
            'page_title' => __d("sweet_forum", "Blocked users"),            
            'users' => $users
        ));
    }
    
    // view conversation
    function view($user_hash = false) {
        if($user_hash === false) throw new NotFoundException();
        
        $from_user = $this->Message->Creator->find('first',
            array(
                'conditions' => array('Creator.hash_id' => $user_hash),
                'contain' => array('Info'),
                'fields' => array('Creator.id', 'Creator.hash_id', 'Info.name'),
            )
        );
        if(empty($from_user)) throw new NotFoundException();
        
        $is_user_block_you = $this->MessageUserBlock->find('first',
            array(
                'conditions' => array('MessageUserBlock.by_user_id' => $from_user['Creator']['id'], 'MessageUserBlock.user_id' => $this->Auth->user('User.id')),
                'fields' => array('MessageUserBlock.id')
            )
        );
        
        // check all not checked messages
        $this->Message->updateAll(
            array('Message.checked' => 1),
            array('Message.from_user_id' => $from_user['Creator']['id'], 'Message.to_user_id' => $this->Auth->user('User.id'))
        );
        
        if($this->request->is('post') && empty($is_user_block_you)) { // if add new message
            $data = array(
                'Message' => array(
                    'to_user_id' => $from_user['Creator']['id'],
                    'from_user_id' => $this->Auth->user('User.id'),
                ),
                'Info' => array(
                    'text' => $this->request->data['Info']['text']
                )
            );
            
            $this->Message->Info->set($this->request->data);
            if($this->Message->Info->validates()) {
                if($this->Message->saveAssociated($data, array('validate' => false))) {
                    // remove coversation form archive
                    $arch = $this->MessageArchived->findByUserIdAndByUserId($from_user['Creator']['id'], $this->Auth->user('User.id'), array('MessageArchived.id'));
                    if(!empty($arch)) {
                        $this->MessageArchived->id = $arch['MessageArchived']['id'];
                        $this->MessageArchived->delete();
                    }
                    
                    $this->Session->setFlash(__d("sweet_forum", "Message added"), 'default', array('class' => 'alert alert-success margin-top15'));
                }
            } else {
                $this->Session->setFlash(__d("sweet_forum", "Error. Message not added"), 'default', array('class' => 'alert alert-danger margin-top15'));
            }
        }
        
        $page = array_key_exists('page', $this->request->query) ? (int) $this->request->query['page'] : 1;
        if($page < 1) $page = 1;
        
        $count = $this->Message->query("
            SELECT COUNT(Message.id) as count, Message.to_user_id, Message.from_user_id FROM ".$this->Message->table." AS Message            
            WHERE
                (
                    (Message.to_user_id = ".$this->Auth->user('User.id')." AND Message.from_user_id = ".$from_user['Creator']['id'].")
                    OR
                    (Message.from_user_id = ".$this->Auth->user('User.id')." AND Message.to_user_id = ".$from_user['Creator']['id'].")
                )
            LIMIT 1
        ");            
        
        $count = $count[0][0]['count'];
        $limit = $this->Message->getLimitString($count, self::MESSAGE_PER_PAGE, $page);        
        
        $messages = $this->Message->query("
            SELECT Message.*, Info.text, Creator.id, Creator.hash_id, CreatorInfo.name, CreatorInfo.avatar FROM ".$this->Message->table." AS Message
            LEFT JOIN ".$this->Message->Info->table." AS Info ON Info.message_id = Message.id
            LEFT JOIN ".$this->Message->Creator->table." AS Creator ON Creator.id = Message.from_user_id
            LEFT JOIN ".$this->Message->Creator->Info->table." AS CreatorInfo ON CreatorInfo.user_id = Message.from_user_id
            WHERE
                (
                    (Message.to_user_id = ".$this->Auth->user('User.id')." AND Message.from_user_id = ".$from_user['Creator']['id'].")
                    OR
                    (Message.from_user_id = ".$this->Auth->user('User.id')." AND Message.to_user_id = ".$from_user['Creator']['id'].")
                )
            ORDER BY Message.created
            LIMIT {$limit}
        ");
        
        if(empty($messages)) throw new NotFoundException();
        
        App::uses('PrettyTime', 'SweetForum.View/Helper');
        App::uses('TopicText', 'SweetForum.Lib');
        
        $this->set(array(
            'page_title' => __d("sweet_forum", "Conversation with")." ".$from_user['Info']['name'],
            'interlocutor' => $from_user,
            'messages' => $messages,
            'is_user_block_you' => !empty($is_user_block_you),
            'current_page' => $page,
            'max_pages' => ceil($count / self::MESSAGE_PER_PAGE),
            'special_min_css' => array('sweet_forum/First/css/venobox/venobox.css'),
            'special_min_js' => array('sweet_forum/First/js/messages/view.js')
        ));
    }
    
    /**
     *  Function to send messages
     */
    function send($to_user = false) {
        $this->autoRender = false;
        
        if(!$this->request->is('post')) throw new NotFoundException();
        
        if($to_user === false) throw new NotFoundException();
        $to_user = trim($to_user);
        if(empty($to_user)) throw new NotFoundException();
        
        $to_user = $this->Message->Creator->find('first',
            array(
                'conditions' => array('Creator.hash_id' => $to_user),
                'contain' => array('Info'),
                'fields' => array('Creator.id', 'Creator.hash_id', 'Info.name'),
            )
        );
        if(empty($to_user)) throw new NotFoundException();
        
        $data = array(
            'Message' => array(
                'to_user_id' => $to_user['Creator']['id'],
                'from_user_id' => $this->Auth->user('User.id'),
            ),
            'Info' => array(
                'text' => $this->request->data['Info']['text']
            )
        );
        
        $error = true;
        $this->Message->Info->set($this->request->data);
        if($this->Message->Info->validates()) {
            if($this->Message->saveAssociated($data, array('validate' => false))) {
                // remove coversation form archive
                $arch = $this->MessageArchived->findByUserIdAndByUserId($to_user['Creator']['id'], $this->Auth->user('User.id'), array('MessageArchived.id'));
                if(!empty($arch)) {
                    $this->MessageArchived->id = $arch['MessageArchived']['id'];
                    $this->MessageArchived->delete();
                }
                
                if(!$this->request->is('ajax')) {
                    $error = false;
                    $this->Session->setFlash(__d("sweet_forum", "Message sent"), 'default', array('class' => 'alert alert-success margin-top15'));
                } else {
                    return json_encode(array(
                        'success' => true,
                        'message' => __d("sweet_forum", "Message sent")
                    ));
                }
            }
        }
        
        if($error) {
            if(!$this->request->is('ajax')) {
                $this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
            } else {
                return json_encode(array(
                    'success' => false,
                    'message' => __d("sweet_forum", "Error"),
                    'errors' => $this->Message->validationErrors
                ));
            }
        }
    }
    
    /**
     *  Function for edit message
     */
    function edit($hash_id = false) {
        if($hash_id === false) throw new NotFoundException();
        
        $message = $this->Message->find('first',
            array(
                'conditions' => array('Message.hash_id' => $hash_id, 'Message.from_user_id' => $this->Auth->user('User.id')),
                'contain' => array('Info'),
                'fields' => array('Message.id', 'Message.to_user_id', 'Info.id', 'Info.text', 'To.hash_id'),
                'joins' =>
                    array(
                        array(
                            'table' => $this->Message->Creator->table,
                            'alias' => 'To',
                            'type' => 'LEFT',
                            'conditions' => array('To.id = Message.to_user_id')
                        )
                    )
            )
        );        
        if(empty($message)) throw new NotFoundException();
        
        if($this->request->is('post')) {
            $data = array(
                'Message' => array(
                    'is_modified' => true
                ),
                'Info' => array(
                    'text' => $this->request->data['Info']['text']
                )
            );
            
            $this->Message->id = $message['Message']['id'];
            $this->Message->Info->id = $message['Info']['id'];            
            if($this->Message->Info->save($data['Info']) && $this->Message->save($data['Message'], false)) {
                $this->Session->setFlash(__d("sweet_forum", "Message updated"), 'default', array('class' => 'alert alert-success margin-top15'));
                $this->redirect(SWEET_FORUM_BASE_URL.'messages/view/'.$message['To']['hash_id']);
            } else {
                $this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
            }
        }
        
        $this->set(array(
            'message' => $message,
            'page_title' => __d("sweet_forum", "Edit message")
        ));
    }
    
    /**
    *   Function for deleting messages (actually its set db status field to -1)
    */
    function delete($hash_id = false) {
        if(!$this->request->is('post') || $hash_id === false) throw new NotFoundException();
        
        $this->autoRender = false;
        
        $check = $this->Message->findByHashIdAndFromUserId($hash_id, $this->Auth->user('User.id'), array('Message.id'));
        if(empty($check)) throw new NotFoundException();
        
        $this->Message->id = $check['Message']['id'];
        $res = $this->Message->saveField('status', '-1');
        
        return json_encode(array('success' => $res ? true : false));
    }
    
    // drop conversation to trash
    function remove_converstaion($from_user = false) {
        $this->autoRender = false;
        
        if(!$this->request->is('post')) throw new NotFoundException();
        
        if($from_user === false) throw new NotFoundException();
        
        $from_user = $this->Message->Creator->find('first',
            array(
                'conditions' => array('Creator.hash_id' => $from_user),
                'contain' => array('Info'),
                'fields' => array('Creator.id'),
            )
        );
        if(empty($from_user)) throw new NotFoundException();
        
        if($from_user['Creator']['id'] == $this->Auth->user('User.id')) throw new NotFoundException();
        
        $check = $this->MessageArchived->findByUserId($from_user['Creator']['id']);
        if(empty($check)) {
            $data = array(
                'MessageArchived' => array(
                    'user_id' => $from_user['Creator']['id'],
                    'by_user_id' => $this->Auth->user('User.id')
                )
            );
            $res = $this->MessageArchived->save($data, false);
        } else {
            $res = true;
        }
        
        return json_encode(array('success' => $res));
    }
    
    // recovery converstaion
    function recovery_converstaion($from_user = false) {
        $this->autoRender = false;
        
        if(!$this->request->is('post')) throw new NotFoundException();
        
        if($from_user === false) throw new NotFoundException();
        
        $from_user = $this->Message->Creator->find('first',
            array(
                'conditions' => array('Creator.hash_id' => $from_user),
                'contain' => array('Info'),
                'fields' => array('Creator.id'),
            )
        );
        if(empty($from_user)) throw new NotFoundException();
        
        $check = $this->MessageArchived->findByUserId($from_user['Creator']['id']);
        if(!empty($check)) {            
            $this->MessageArchived->id = $check['MessageArchived']['id'];
            $res = $this->MessageArchived->delete();
        } else {
            $res = true;
        }
        
        return json_encode(array('success' => $res));
    }
    
    function block_user($hash_id = false) {
        $this->autoRender = false;
        
        if($hash_id === false) $this->redirect404();
        
        $user = $this->Message->Creator->find('first',
            array(
                'conditions' => array('Creator.hash_id' => $hash_id, 'Creator.id !=' => $this->Auth->user('User.id')),
                'fields' => array('Creator.id'),
            )
        );
        if(empty($user)) throw new NotFoundException();
        
        $check = $this->MessageUserBlock->findByUserIdAndByUserId($user['Creator']['id'], $this->Auth->user('User.id'), array('MessageUserBlock.id'));
        if(!empty($check)) throw new NotFoundException();
        
        $data = array(
            'MessageUserBlock' => array(
                'user_id' => $user['Creator']['id'],
                'by_user_id' => $this->Auth->user('User.id')
            )
        );        
        $res = $this->MessageUserBlock->save($data, false);
        
        return json_encode(array('success' => (bool) $res, 'message' => __d("sweet_forum", "User successfully blocked")));        
    }
    
    function recovery_user($hash_id = false) {
        $this->autoRender = false;
        
        if($hash_id === false) $this->redirect404();
        
        $user = $this->Message->Creator->find('first',
            array(
                'conditions' => array('Creator.hash_id' => $hash_id),
                'fields' => array('Creator.id'),
            )
        );
        if(empty($user)) throw new NotFoundException();
        
        $find = $this->MessageUserBlock->find('first',
            array(                
                'conditions' => array('MessageUserBlock.user_id' => $user['Creator']['id'], 'MessageUserBlock.by_user_id' => $this->Auth->user('User.id')),
                'fields' => array('MessageUserBlock.id')
            )
        );
        
        $res = false;        
        
        if(!empty($find)) $res = (bool) $this->MessageUserBlock->delete($find['MessageUserBlock']['id']);        
        
        return json_encode(array('success' => $res));
    }
    
    // get
    /**
     *  Return number of unread conversations for currently loged user
     *  @return int
     */
    function get_unread() {
        $this->autoRender = false;
        
        $conversations = $this->Message->query("
            SELECT Message.id FROM ".$this->Message->table." AS Message
            LEFT JOIN ".$this->MessageArchived->table." AS Archived ON Archived.user_id = Message.from_user_id
            LEFT JOIN ".$this->MessageUserBlock->table." AS Blocked ON Blocked.user_id = Message.from_user_id            
            WHERE Message.to_user_id = ".$this->Auth->user('User.id')." AND Message.status = 0 AND Message.checked = 0 AND Archived.user_id IS NULL AND Blocked.user_id IS NULL
            GROUP BY Message.from_user_id            
        ");
        
        echo count($conversations);
    }
}
