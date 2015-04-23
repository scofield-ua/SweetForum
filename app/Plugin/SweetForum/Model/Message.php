<?php
class Message extends SweetForumAppModel {
    public $hasOne = array(
        'Info' => array(
            'className' => 'SweetForum.MessageInfo',
            'foreignKey' => 'message_id'
        )
    );
    
    public $belongsTo = array(        
        'Creator' => array(
            'className' => 'SweetForum.User',
            'foreignKey' => 'from_user_id'
        ),
        'Archived' => array(
            'className' => 'SweetForum.MessageArchived',
            'foreignKey' => 'from_user_id'        
        )
    );
    
    function beforeSave($options = array()) {
        $this->data['Message']['hash_id'] = md5(date('YmdHis').microtime().rand(1,100));
        
        return true;
    }
}
