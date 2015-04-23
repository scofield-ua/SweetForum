<?php
class UserActivity extends SweetForumAppModel {
    public $name = 'UserActivity';
    public $useTable = 'users_activity';

    public $belongsTo = array(
        'Owner' => array(
            'className' => 'SweetForum.User',
            'foreignKey' => 'from_user_id'
        ),
        'OnUser' => array(
            'className' => 'SweetForum.User',
            'foreignKey' => 'on_user_id'
        )
    );

    public $validate = array(
        'on_user_id' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Только цифры',
                'required' => true
            )
        ),
        'from_user_id' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Только цифры',
                'required' => true
            )
        ),
        'flag' => array(
            'numeric' => array(
                'rule' => 'numeric',
                'message' => 'Только цифры',
                'required' => true
            )
        ),
        'message' => array(
            'length' => array(
                'rule' => array('between', 1, 1000),
                'message' => 'Поле не должно иметь больше 1000-и символов и не должно быть пустым',
                'required' => true
            ),
        ),
    );

    function beforeSave() {
        if(array_key_exists('Info', $this->data)) {
            if(array_key_exists('username', $this->data['Info'])) $this->data['Info']['username'] = strtolower($this->data['Info']['username']);
        }
    }
}
