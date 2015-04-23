<?php
class CommentActivity extends SweetForumAppModel {
    public $useTable = 'comments_activity';

    public $belongsTo = array(
        'Comment' => array(
            'className' => 'SweetForum.Comment',
            'foreignKey' => 'comment_id'
        )
    );
}