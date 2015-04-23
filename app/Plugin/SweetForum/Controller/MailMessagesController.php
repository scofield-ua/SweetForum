<?php
/**
 *  You can use this class to send emails to users 
 */
class MailMessagesController extends SweetForumAppController {
    public $uses = array('SweetForum.User', 'SweetForum.Topic');
    
    /**
     *  Send email to user with defined ID
     *  @param $to int User id form `users` table
     *  @param $type int Notification type (1 - new topic comment, 2 - new private message)
     *  @param $options array Different options
     *  @throws NotFoundExceiption If user not found with such id
     *  @throws NotImplementedException If $type is incorrect
     *  @throws ForbiddenException If notification disable by user
     *  @return boolean TRUE if success, FALSE if fail
     */
    function send($to, $type, $options = array()) {
        $this->autoRender = false;
        
        $to = (int) $to;
        
        $receiver_user = $this->User->find('first',
            array(
                'conditions' => array('User.id' => $to),
                'contain' => array('Notification')
            )
        );
        if(empty($receiver_user)) throw new NotFoundException();
        
        $type = (int) $type;
        switch($type) {
            case 1 :
                $topic = $this->Topic->find('first',
                    array(
                        'conditions' => array('Topic.id' => $options['topic_id']),
                        'contain' => array(),
                        'fields' => array('Topic.name', 'Topic.url')
                    )
                );
                
                if(!$receiver_user['Notification']['new_topic_comment']) throw new ForbiddenException();
                
                $topic_url = self::WEBSITE."/topic/".$topic['Topic']['url'];
                
                $message = __d("sweet_forum", "You have new comment in your topic.");
                $message .= "<br/> <a href='$topic_url'>".$topic['Topic']['name']."</a>";
            break;
            case 2 :
                if(!$receiver_user['Notification']['new_private_message']) throw new ForbiddenException();
                
                $message = __d("sweet_forum", "You receive new private message.");
            break;
            default :
                throw new NotImplementedException();
            break;
        }
        
        $this->SweetMailSender = $this->Components->load('SweetForum.SweetMailSender');
        
        $settings = array(
            'theme' => 'First',
            'view' => 'SweetForum.notification',
            'subject' => __d("sweet_forum", "Notification"),
            'from_email' => 'my@php720.com',
            'from_name' => 'SweetForum',
            'data' => array(
                'website' => self::WEBSITE,
                'message' => $message
            )
        );
        
        return $this->SweetMailSender->send($receiver_user['User']['email'], $settings);        
    }
}
