<?php
class MessageUserBlock extends SweetForumAppModel {
    public $useTable = 'messages_users_blocked';
    
    public $belongsTo = array(
        'User' => array(
            'className' => 'SweetForum.User',
            'foreginKey' => 'user_id'
        )
    );
}
