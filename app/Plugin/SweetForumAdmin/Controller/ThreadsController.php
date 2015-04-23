<?php
class ThreadsController extends SweetForumAdminAppController {
    public $uses = array('SweetForum.Thread');

    function index() {
        $threads = $this->Thread->find('all');

        $this->set(array(
			'page_title' => __d('sweet_forum', 'Threads'),
            'threads' => $threads,
			'current_nav' => '/admin/threads/index',
		));
    }

    function add() {
        if($this->request->is('post')) {
            if($this->Thread->save($this->request->data)) {
                $this->Session->setFlash(__d("sweet_forum", "Topic added"), 'default', array('class' => 'alert alert-success margin-top15'));
            } else {
                $this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
            }
        }

        $this->set(array(
			'page_title' => __d('sweet_forum', 'Add thread'),
			'current_nav' => '/admin/threads/index',
		));
    }
	
	function edit($id = null) {
		if($id === null) throw new NotFoundException();
		
		$thread = $this->Thread->findById($id);
		if(empty($thread)) throw new NotFoundException();
		
		if($this->request->is('post')) {
            $this->Thread->id = $id;
			
			if($this->Thread->save($this->request->data)) {
                $this->Session->setFlash(__d("sweet_forum", "Updated"), 'default', array('class' => 'alert alert-success margin-top15'));
				$this->redirect($this->here);
            } else {
                $this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
            }
        }
		
		$this->set(array(
			'page_title' => __d('sweet_forum', 'Edit thread'),
			'thread' => $thread,
			'current_nav' => '/admin/threads/index',
		));
	}
	
	function delete($id = null) {
		$this->autoRender = false;
		
		if($id === null) throw new NotFoundException();
		
		$thread = $this->Thread->findById($id);
		if(empty($thread)) throw new NotFoundException();
		
		if($this->Thread->delete($id)) {
			$this->Session->setFlash(__d("sweet_forum", "Deleted"), 'default', array('class' => 'alert alert-success margin-top15'));			
		} else {
			$this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
		}
		
		$this->redirect(SWEET_FORUM_BASE_URL.'admin/threads');
	}
}