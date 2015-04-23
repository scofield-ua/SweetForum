<?php
$user_name = isset($user_name) ? $user_name : "user";
?>

<div class="modal fade" id="user-profile-view-message-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?= __d("sweet_forum", "Send message to %s", array($user_name)); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                    echo $this->Form->create('Message', array('class' => 'form', 'url' => SWEET_FORUM_BASE_URL.'messages/send/'.$to_user));
                    echo $this->Form->input('Info.text', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Message text"), 'type' => 'textarea'));
                    echo $this->Form->submit(__d("sweet_forum", "Send"), array('class' => 'btn btn-primary', 'data-loading-text' => __d("sweet_forum", 'Loading').'...'));
                    echo $this->Form->end();
                ?>
                <div class="alert hide margin-top15">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="user-profile-view-report-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?= __d("sweet_forum", "Report user %s", array($user_name)); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                    echo $this->Form->create('UserReport', array('class' => 'form', 'url' => SWEET_FORUM_BASE_URL.'users/report/'.$to_user));
                    echo $this->Form->input('UserReport.report_message', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Why you want to report this user"), 'type' => 'textarea'));
                    echo $this->Form->submit(__d("sweet_forum", "Send"), array('class' => 'btn btn-primary', 'data-loading-text' => __d("sweet_forum", 'Loading').'...'));
                    echo $this->Form->end();
                ?>
                <div class="alert hide margin-top15">
                    
                </div>
            </div>
        </div>
    </div>
</div>