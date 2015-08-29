<?php
class Topic extends SweetForumAppModel {
    public $belongsTo = array(
        'Creator' => array(
            'className' => 'SweetForum.User',
            'foreignKey' => 'user_id'
        ),
        'Thread' => array(
            'className' => 'SweetForum.Thread',
            'foreignKey' => 'thread_id'
        )
    );

    public $hasMany = array(
        'Comment' => array(
            'className' => 'SweetForum.Comment',
            'foreignKey' => 'topic_id',
            'conditions' => array(
                'AND' => array(
                    'answer_to' => null,
                    'status' => 0
                )
            )
        ),
        'Like' => array(
            'className' => 'SweetForum.Like',
            'foreignKey' => 'type_id',
            'conditions' => array('AND' => array('type' => 0)),
            'fields' => array('id', 'user_id')
        ),
    );

    public $validate = array(
        'thread_id' => array(
            'int' => array(
                'rule' => 'naturalNumber',
                'required' => true,
            )
        ),
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
                'message' => "Field value can't be empty"
            ),
            'l' => array(
                'rule' => array('between', 1, 250),
                'message' => "Field value length must be between 1 and 250 characters"
            )
        ),
        'text' => array(
            'ne' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => "Field value can't be empty"
            )
        ),
        'url' => array(
            'ne' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => "Field value can't be empty"
            ),
            'l' => array(
                'rule' => array('between', 1, 250),
                'message' => "Field value length must be between 1 and 250 characters"
            ),
            'reg_exp' => array(
                'rule'    => '/^([a-z0-9]+-)*[a-z0-9]+/i',
                'message' => 'Please use Latin letters, numbers and "-" symbol'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => "This address is already taken",
            ),
        )
    );

    function afterFind($results, $primary = false) {
        if(!isset($this->dontTouchTitle)) {
            if(count($results) == 1) {
                if(array_key_exists('Topic', $results)) {
                    if(array_key_exists('name', $results['Topic'])) $results['Topic']['name'] = h($results['Topic']['name']);
                }
            } else {
                foreach($results as $key => $result) {
                    if(is_array($result)) {
                        if(array_key_exists('Topic', $result)) {
                            if(array_key_exists('name', $result['Topic'])) $results[$key]['Topic']['name'] = h($result['Topic']['name']);
                        }
                    }
                }
            }
        }
        return $results;
    }

    function bindActivity() {
        $this->bindModel(
            array('hasMany' => array(
                    'Activity' => array(
                        'className' => 'TopicActivity',
                        'foreignKey' => 'topic_id'
                    )
                )
            )
        );
    }
}
