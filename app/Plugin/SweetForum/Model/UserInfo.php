<?php
class UserInfo extends SweetForumAppModel {
    public $name = 'UserInfo';
    public $useTable = 'users_info';    

    public $validate = array(
        'username' => array(
            'reg_exp' => array(
                'rule' => '/^[a-zA-Z0-9]+/',
                'message' => 'This field can contain only A-Z symbols and numbers',
                'allowEmpty' => true
            ),
            'length' => array(
                'rule' => array('between', 1, 100),
                'message' => 'Поле не должно иметь больше 100-а символов',
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This address is already taken',
            ),
        ),
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Empty value',
                'required' => true
            )
        )
    );

    function beforeSave($options = array()) {
        if(array_key_exists('Info', $this->data)) {
            if(array_key_exists('username', $this->data['Info'])) $this->data['Info']['username'] = strtolower($this->data['Info']['username']);
        }
    }
}
