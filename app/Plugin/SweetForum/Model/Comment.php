<?php
class Comment extends SweetForumAppModel {
    /*public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->virtualFields['likes'] = sprintf(
            'COUNT(%s.id)', $this->alias
        );
    }*/
    
    public $hasOne = array(
        'Info' => array(
            'className' => 'SweetForum.CommentInfo',
            'foreignKey' => 'comment_id'
        )
    );
    
    public $belongsTo = array(
        'Creator' => array(
            'className' => 'SweetForum.User',
            'foreignKey' => 'user_id'
        )
    );

    public $hasMany = array(
        'Answer' => array(
            'className' => 'SweetForum.Comment',
            'foreignKey' => 'answer_to',
            'conditions' => array(
                'AND' => array(
                    'status' => 0
                )
            )
        ),
        'Like' => array(
            'className' => 'SweetForum.Like',
            'foreignKey' => 'type_id',
            'conditions' => array('type' => 1),
        ),
    );
    
    /*public $virtualFields = array(
        'likes' => 'COUNT(Like.id)'
    );*/

    public $validate = array(
        'hash_id' => array(
            'req' => array(
                'rule' => array('maxLength', 100),
                'required' => true
            )
        ),
        'user_id'  => array(
            'int' => array(
                'rule' => 'naturalNumber',
                'required' => true,
            )
        ),
        'topic_id'  => array(
            'int' => array(
                'rule' => 'naturalNumber',
                'required' => true,
            )
        ),
        'answer_to'  => array(
            'int' => array(
                'rule' => 'naturalNumber',
                'allowEmpty' => true
            )
        ),
    );

    function bindTopic() {
        $this->bindModel(
            array('belongsTo' => array(
                    'Topic' => array(
                        'className' => 'SweetForum.Topic',
                        'foreignKey' => 'topic_id',
                        'conditions' => array('Topic.status' => 0)
                    )
                )
            )
        );
    }
}
