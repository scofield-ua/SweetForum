<?php
class CommentInfo extends SweetForumAppModel {
    public $useTable = 'comments_info';

    public $validate = array(
        'comment_id'  => array(
            'int' => array(
                'rule' => 'naturalNumber',
                'required' => true,
            )
        ),
        'text' => array(
            'ne' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Поле не может быть пустым'
            )
        ),
        'is_modified'  => array(
            'bool' => array(
                'rule' => array('boolean'),
                'allowEmpty' => true,
            )
        ),
    );
}
