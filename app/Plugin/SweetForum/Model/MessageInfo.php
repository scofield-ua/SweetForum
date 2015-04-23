<?php
class MessageInfo extends SweetForumAppModel {
    public $useTable = 'messages_info';
    
    public $validate = array(
        'text' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Field value is empty',
                'required' => true
            )
        )
    );
}
