<?php
class UserInfo extends SweetForumAppModel {
    public $name = 'UserInfo';
    public $useTable = 'users_info';    

    public $validate = array(
        'username' => array(
            'reg_exp' => array(
                'rule' => '/^[a-zA-Z0-9]+/',
                'message' => 'Поле может иметь только цифры и буквы латинского алфавита',
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
                'message' => 'Значение поля не может быть пустым',
                'required' => true
            )
        )
    );

    function beforeSave() {
        if(array_key_exists('Info', $this->data)) {
            if(array_key_exists('username', $this->data['Info'])) $this->data['Info']['username'] = strtolower($this->data['Info']['username']);
        }
    }
}
