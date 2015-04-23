<div class='row'>
    <div class='col-md-12'>
        <div class="page-header"><h1><small><?= $page_title; ?></small></h1></div>
    </div>
</div>

<div class='row profile-page'>    
    <div class='col-md-2 text-center'>
        <?= $this->element('profile/default-left-part'); ?>
        <div class='clearfix'></div>
    </div>

    <div class='col-md-10 summary'>
        <?php
            echo $this->Form->create('User', array('class' => 'form'));
            
            echo "<h4>".__d('sweet_forum', 'Privacy')."</h4>";
            echo $this->Form->input('Privacy.show_social_link', array('before' => '<label>', 'after' => __d("sweet_forum", "Show link to your social page").' </label>', 'div' => 'checkbox', 'type' => 'checkbox', 'label' => false, 'checked' => $user['Privacy']['show_social_link']));
            echo $this->Form->input('Privacy.stop_private_messages', array('before' => '<label>', 'after' => __d("sweet_forum", "Disable private messaging with you").' </label>', 'div' => 'checkbox', 'type' => 'checkbox', 'label' => false, 'checked' => $user['Privacy']['stop_private_messages']));
            
            echo "<h4 class='margin-top15'>".__d('sweet_forum', 'Notifications')."</h4>";
            //echo $this->Form->input('Notification.new_topic_comment', array('before' => '<label>', 'after' => __d("sweet_forum", "Receive mail when someone comment your topic").' </label>', 'div' => 'checkbox', 'type' => 'checkbox', 'label' => false, 'checked' => $user['Notification']['new_topic_comment']));
            echo $this->Form->input('Notification.new_private_message', array('before' => '<label>', 'after' => __d("sweet_forum", "Receive mail when someone sent you private message").' </label>', 'div' => 'checkbox', 'type' => 'checkbox', 'label' => false, 'checked' => $user['Notification']['new_private_message']));
            echo $this->Form->submit(__d("sweet_forum", "Update"), array('class' => 'btn btn-primary'));
            echo $this->Form->end();
        ?>
    </div>
</div>
<?php
    $special_min_js[] = 'sweet_forum/First/js/messages/unread.js';
    $this->set('special_min_js', $special_min_js);
?>