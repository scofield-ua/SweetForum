<?php
class Blog extends SweetForumAppModel {
    public $name = 'Blog';
    public $useTable = 'blog_posts';

    public $validate = array(
        'user_id'  => array(
            'int' => array(
                'rule' => 'naturalNumber',
                'required' => true,
            )
        ),
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
        'text' => array(
            'ne' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Поле не может быть пустым'
            )
        ),
        'url' => array(
            'ne' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Поле не может быть пустым'
            ),
            'l' => array(
                'rule' => array('between', 1, 250),
                'message' => 'Поле может быть от 1 до 250 символов'
            ),
            'reg_exp' => array(
                'rule'    => '/^([a-z0-9]+-)*[a-z0-9]+/i',
                'message' => 'Можно использовать цифры, латиницу, дефис'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Уже занято',
            ),
        )
    );
}
