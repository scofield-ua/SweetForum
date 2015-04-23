<?php
class Like extends SweetForumAppModel {
    public $name = 'Like';
    public $useTable = 'likes';    

    public $belongsTo = array(
        'Owner' => array(
            'className' => 'SweetForum.User',
            'foreignKey' => 'user_id',
            'fields' => array('id', 'email')
        )
    );

    public $validate = array(
        'user_id'  => array(
            'int' => array(
                'rule' => 'naturalNumber',
                'required' => true,
            )
        ),
        'type_id' => array(
             'int' => array(
                'rule' => 'naturalNumber',
                'required' => true,
            )
        ),
        'type' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'required' => true,
            )
        )
    );
    
    function beforeSave($options = array()) {
        if(array_key_exists('Like', $this->data)) {
            if(!array_key_exists('hash_id', $this->data['Like'])) {
                $this->data['Like']['hash_id'] = md5(date('YmdHis').rand(1,1000).microtime());
            }
        }
        
        return true;
    }
    
    function afterFind($results = array(), $primary = false) {
        #pr(count($results));
        return $results;
    }
}
