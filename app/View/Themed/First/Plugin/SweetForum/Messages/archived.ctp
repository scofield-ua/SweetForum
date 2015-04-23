<div class='row'>
    <div class="page-header col-md-12">
        <h1><?= __d("sweet_forum", "Conversations"); ?> &middot; <?= $current_page; ?></h1>
    </div>
</div>

<div class='row'>
    <div class='col-md-3 text-center'>
        <?= $this->element('messages/nav'); ?>
    </div>
    
    <!-- Conversations -->
    <div class='col-md-9 conversations'>
        <?= $this->element('messages/conversations', array('conversations' => $conversations, 'recovery_button' => true)); ?>
    </div>
</div>

<?php
$special_min_js[] = 'sweet_forum/First/js/messages/conversations.js';
$this->set('special_min_js', $special_min_js);        
?>