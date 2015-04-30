<div class='row'>
    <div class="col-md-12">
        <div class="page-header">
            <h1><small><?= __d("sweet_forum", "Conversations"); ?> &middot; <?= $current_page; ?></small></h1>
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-md-3 text-center'>
        <?= $this->element('messages/nav'); ?>
    </div>

    <!-- Conversations -->
    <div class='col-md-9 conversations'>
        <?= $this->element('messages/conversations', array('conversations' => $conversations)); ?>
    </div>
</div>

<?php
$special_min_js[] = 'sweet_forum/First/js/messages/conversations.js';
$this->set('special_min_js', $special_min_js);
?>