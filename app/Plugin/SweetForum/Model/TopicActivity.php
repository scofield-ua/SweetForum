<?php
class TopicActivity extends SweetForumAppModel {
    public $useTable = 'topics_activity';

    public $belongsTo = array(
        'Topic' => array(
            'className' => 'SweetForum.Topic',
            'foreignKey' => 'topic_id'
        ),
        'Owner' => array(
            'className' => 'SweetForum.User',
            'foreignKey' => 'user_id'
        )
    );
}