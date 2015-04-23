<?php
class Thread extends SweetForumAppModel {
    public $hasMany = array(
        'Topic' => array(
            'className' => 'SweetForum.Topic',
            'conditions' => array('status !=' => -1),
            'counterCache' => true
        )
    );

    public $validate = array(
        'name' => array(
            'ne' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Поле не может быть пустым'
            ),
            'l' => array(
                'rule' => array('between', 1, 250),
                'message' => 'Поле может быть от 1 до 250 символов'
            )
        ),
        'description' => array(
            'ne' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Поле не может быть пустым'
            ),
            'l' => array(
                'rule' => array('between', 1, 1000),
                'message' => 'Поле может быть от 1 до 1000 символов'
            )
        ),
        'url' => array(
            'ne' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Поле не может быть пустым'
            ),
            'l' => array(
                'rule' => array('between', 1, 100),
                'message' => 'Поле может быть от 1 до 100 символов'
            ),
            'reg_exp' => array(
                'rule'    => '/^([a-z0-9]+-)*[a-z0-9]+/i',
                'message' => 'Можно использовать цифры, латиницу, дефис'
            ),
            'unique'=>array(
                'rule' => 'isUnique',
                'message' => 'Уже занято',
            ),
        )
    );
}
