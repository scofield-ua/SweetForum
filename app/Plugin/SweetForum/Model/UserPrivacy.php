<?php
class UserPrivacy extends SweetForumAppModel {
    public $name = 'UserPrivacy';
    public $useTable = 'users_privacy';

    public $validate = array(
        'show_social_link' => array(
            'bool' => array(
                'rule' => array('boolean'),
                'message' => 'Не правильное значение',
                'allowEmpty' => false
            ),
        ),
    );
}
