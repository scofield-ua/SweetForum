<?php
App::uses('Security', 'Utility');
class User extends SweetForumAppModel {
    public $validationDomain = 'validation';
    
    public $hasOne = array(
        'Info' => array(
            'className' => 'SweetForum.UserInfo',
            'dependent' => true
        ),
        'Privacy' => array(
            'className' => 'SweetForum.UserPrivacy',
            'dependent' => true
        ),
        'Notification' => array(
            'className' => 'SweetForum.UserNotification',
            'dependent' => true
        ),
    );
    
    public $validate = array(
        'email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Field value is empty',
                'required' => true
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Email address is incorrect',
                'required' => true
            ),
            'unique' => array(
                'rule' => 'isUniqueEmail',
                'message' => 'Email address is already using',
                'required' => true
            )
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Field value is empty',
                'required' => true
            )
        )
    );  

    function isUniqueEmail() {
        $check = $this->findByEmail($this->data['User']['email'], array('id'));
        return empty($check);
    }
    
    function bindActivity() {
        $this->bindModel(
            array('belongsTo' => array(
                    'Activity' => array(
                        'className' => 'SweetForum.UserActivity',
                        'foreignKey' => 'user_id'
                    )
                )
            )
        );
    }
    
    function bindResendPassword() {
        $this->bindModel(
            array('belongsTo' => array(
                    'ResendPassword' => array(
                        'className' => 'SweetForum.UserResendPassword',
                        'foreignKey' => 'id'
                    )
                )
            )
        );
    }
}
