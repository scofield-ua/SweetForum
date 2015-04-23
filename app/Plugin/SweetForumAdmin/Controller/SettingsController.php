<?php
class SettingsController extends SweetForumAdminAppController {
    
    function index() {
        $settings = $this->Setting->find('first');
        
        if($this->request->is('post')) {
            $this->Setting->id = $settings['Setting']['id'];
            
			if($this->Setting->save($this->request->data, false)) {
				$this->Session->setFlash(__d("sweet_forum", "Settings saved"), 'default', array('class' => 'alert alert-success margin-top15'));
                $this->redirect($this->here);
			} else {
				$this->Session->setFlash(__d("sweet_forum", "Error"), 'default', array('class' => 'alert alert-danger margin-top15'));
			}
		}
        
        $this->set(array(
			'page_title' => __d('sweet_forum', 'Settings'),
            'settings' => $settings,
			'current_nav' => '/admin/settings',
		));
    }
}
