<?php
/*
*   Флаги
*   -1 - пожаловались
*/
class CommentActivitiesController extends SweetForumAppController {
    function mark($hash_id = false) {
        $this->autoRender = false;

        if($hash_id === false) throw new NotFoundException(4);

        // check comment for existing
        $this->CommentActivity->Comment->recursive = -1;
        $f = $this->CommentActivity->Comment->findByHashId($hash_id, array('id', 'topic_id'));
        if(empty($f)) throw new NotFoundException(4);

        $ret = array('message' => __d("sweet_forum", "Access error"), 'success' => false);
        if($this->request->is('ajax')) {
            $this->CommentActivity->recursive = -1;
            $check = $this->CommentActivity->findByCommentIdAndUserIdAndFlag($f['Comment']['id'], $this->Auth->user('User.id'), -1); // проверяем, нет ли записи для этого коммента от этого пользователя
            if(!empty($check)) {
                $ret['message'] = __d("sweet_forum", "You already report this comment");
            } else {
                $data = array(
                    'CommentActivity' => array(
                        'comment_id' => $f['Comment']['id'],
                        'user_id' => $this->Auth->user('User.id'),
                        'flag' => -1
                    )
                );

                $save = $this->CommentActivity->save($data, false);
                if($save) {
                    $ret['message'] = __d("sweet_forum", "Report accepted. This comment will be removed after 10 reports");
                    $ret['success'] = true;
                } else {
                    $ret['message'] = __d("sweet_forum", "Error. Reportd not accepted");
                }                

                // reports count
                $count = $this->CommentActivity->find('count',
                    array(
                        'recursive' => -1,
                        'conditions' => array(
                            'comment_id' => $f['Comment']['id'],
                            'flag' => -1
                        ),
                        'contain' => array()
                    )
                );

                if($count >= 10) {
                    $data = array('status' => '-1');
                    $this->CommentActivity->Comment->id = $f['Comment']['id'];
                    $this->CommentActivity->Comment->save($data, false);

                    // удаляем кеш
                    $this->loadModel('Topic');
                    $this->Topic->recursive = -1;
                    $topic = $this->Topic->findById($f['Comment']['topic_id'], array('url'));
                    if(!empty($topic)) Cache::delete("sf_topic_".md5($topic['Topic']['url']), "default");
                }
            }

            return json_encode($ret);
        } else {
            throw new NotFoundException();
        }
    }
}