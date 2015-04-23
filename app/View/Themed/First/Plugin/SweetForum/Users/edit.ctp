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
            echo $this->Form->input('Info.name', array('label' => __d("sweet_forum", "Name"), 'value' => $user_data['Info']['name'], 'class' => 'form-control', 'div' => 'form-group'));
            echo $this->Form->input('Info.username', array('label' => __d("sweet_forum", "Profile page username"), 'value' => $user_data['Info']['username'], 'class' => 'form-control', 'div' => 'form-group'));
            echo $this->Form->submit(__d("sweet_forum", "Update"), array('class' => 'btn btn-primary'));
            echo $this->Form->end();
            echo $this->Session->flash();
        ?>
    </div>
</div>
<?php
    $special_min_js[] = 'sweet_forum/First/js/messages/unread.js';
    $this->set('special_min_js', $special_min_js);
?>