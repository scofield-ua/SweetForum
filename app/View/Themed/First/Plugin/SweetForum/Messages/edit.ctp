<div class='row'>
    <div class="page-header col-md-12 text-center">
        <h1>
            <?=
                $this->Html->link("<span class='glyphicon glyphicon-chevron-left'></span>", SWEET_FORUM_BASE_URL.'messages/view/'.$message['To']['hash_id'], array('escape' => false, 'title' => __d("sweet_forim", "back"), 'class' => 'message-header-back'))." ".
                __d("sweet_forum", "Back to conversation");
            ?>
        </h1>
    </div>
</div>
<div class='row margin-top15'>    
    <div class='col-md-8 col-md-offset-2'>
        <?php
            echo $this->Form->create('Message');
            echo $this->Form->input('Info.text', array('label' => false, 'div' => 'form-group', 'class' => 'form-control', 'placeholder' => __d('sweet_forum', 'Type your message here'), 'type' => 'textarea', 'error' => false, 'value' => $message['Info']['text']));
            echo $this->Form->submit(__d("sweet_forum", "Update"), array('class' => 'btn btn-primary'));
            echo $this->Session->flash();
            echo $this->Form->end();
        ?>
    </div>
</div>