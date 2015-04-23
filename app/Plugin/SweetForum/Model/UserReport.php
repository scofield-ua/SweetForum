<?php
class UserReport extends SweetForumAppModel {
    public $name = 'UserReport';
    public $useTable = 'users_reports';
    
    public $belongsTo = array(
        'User' => array(
            'className' => 'SweetForum.User'
        ),
        'ByUser' => array(
            'className' => 'SweetForum.User'
        )
    );
}
