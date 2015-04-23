<?php
/*
*   Флаги
*   -1 - пожаловались
*/
class TopicActivitiesController extends SweetForumAppController {
    function mark($url = false) {
        $this->autoRender = false;

        if($url === false) throw new NotFoundException(4);

        // check topic for existing
        $this->TopicActivity->Topic->recursive = -1;
        $f = $this->TopicActivity->Topic->findByUrl($url, array('id', 'user_id'));
        if(empty($f)) throw new NotFoundException(4);

        if($f['Topic']['user_id'] == $this->Auth->user('User.id')) throw new NotFoundException();

        $ret = array('message' => __d("sweet_forum", "Access error"), 'success' => false);
        if($this->request->is('ajax')) {
            $this->TopicActivity->recursive = -1;
            // check, maybe user already report such topic
            $check = $this->TopicActivity->findByTopicIdAndUserIdAndFlag($f['Topic']['id'], $this->Auth->user('User.id'), -1);
            if(!empty($check)) {
                $ret['message'] = __d("sweet_forum", "You already report this topic");
            } else {
                $data = array(
                    'TopicActivity' => array(
                        'topic_id' => $f['Topic']['id'],
                        'user_id' => $this->Auth->user('User.id'),
                        'flag' => -1
                    )
                );

                $save = $this->TopicActivity->save($data, false);
                if($save) {
                    $ret['message'] = __d("sweet_forum", "Report accepted. This topic will be removed after 20 reports");
                    $ret['success'] = true;
                } else {
                    $ret['message'] = __d("sweet_forum", "Error. Reportd not accepted");
                }                

                // reports count
                $count = $this->TopicActivity->find('count',
                    array(
                        'recursive' => -1,
                        'conditions' => array(
                            'topic_id' => $f['Topic']['id'],
                            'flag' => -1
                        ),
                        'contain' => array()
                    )
                );

                if($count >= 20) {
                    $data = array('status' => '-1');
                    $this->TopicActivity->Topic->id = $f['Topic']['id'];
                    $this->TopicActivity->Topic->save($data, false);

                    // удаляем кеш
                    Cache::delete("sf_topic_".md5($url), "default");
                }
            }

            return json_encode($ret);
        } else {
            throw new NotFoundException();
        }
    }
}