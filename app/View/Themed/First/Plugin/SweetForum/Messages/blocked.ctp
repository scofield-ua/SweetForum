<div class='row'>
    <div class="page-header col-md-12">
        <h1><?= __d("sweet_forum", "Blocked users"); ?></h1>
    </div>
</div>

<div class='row'>
    <div class='col-md-3 text-center'>
        <?= $this->element('messages/nav'); ?>
    </div>
    
    <!-- Conversations -->
    <div class='col-md-9 conversations'>
        <?php
            if(empty($users)) {
                echo "<p class='text-muted text-center'>".__d("sweet_forum", "Nothing yet")."</p>";
            } else {
                foreach($users as $user) {                    
                    $restore = "<span class='glyphicon glyphicon-share-alt'></span>";
                    
                    echo '
                        <div class="col-xs-6 col-md-2 text-center item">
                            <div class="thumbnail">
                                '.$this->Html->image($user['User']['Info']['avatar']).'
                                <div class="caption">
                                    '.$this->Html->link($user['User']['Info']['name'], SWEET_FORUM_BASE_URL.'u/'.$user['User']['Info']['username'], array('class' => 'btn btn-default btn-xs')).'
                                    '.$this->Html->link($restore, SWEET_FORUM_BASE_URL.'messages/recovery_user/'.$user['User']['hash_id'], array('class' => 'btn btn-primary btn-xs recovery-user', 'escape' => false, 'title' => __d('sweet_forum', 'Recover user'))).'
                                </div>
                            </div>
                        </div>
                    ';
                }
            }
        ?>
    </div>
</div>

<?php
$special_min_js[] = 'sweet_forum/First/js/messages/conversations.js';
$this->set('special_min_js', $special_min_js);        
?>