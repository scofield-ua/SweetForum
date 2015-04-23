<?php
class TopicsController extends SweetForumAdminAppController {
    public $uses = array('SweetForum.Topic');

    function index() {
        $current_page = array_key_exists('page', $this->request->query) ? (int) $this->request->query['page'] : 1;

        $this->paginate = array(
            'page' => $current_page,
            'contain' => array(
                'Creator' => array(
                    'fields' => array('Creator.id'),
                    'Info' => array(
                        'fields' => array('Info.name', 'Info.username', 'Info.avatar')
                    )
                )
            ),
            'limit' => 25,
            'order' => 'Topic.created DESC'
        );

        $topics = $this->paginate($this->Topic);

        $this->set(array(
            'page_title' => __d('sweet_forum', 'Topics').' \ '.__d('sweet_forum', 'Recent topics'),
            'topics' => $topics,
            'current_page' => $current_page
        ));
    }
}
