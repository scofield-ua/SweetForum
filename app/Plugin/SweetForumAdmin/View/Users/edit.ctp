<div class='row'>
    <div class='col-md-12'>
        <div class="page-header">
            <h3><?= $page_title; ?></h3>
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <?php
            echo $this->Form->create('User', array('class' => 'form'));
            echo $this->Form->input('User.email', array('class' => 'form-control', 'div' => 'form-group', 'value' => $user['User']['email'], 'readonly' => true));
            echo $this->Form->input('Info.name', array('class' => 'form-control', 'div' => 'form-group', 'value' => $user['Info']['name'], 'label' => __d('sweet_forum', 'Name')));
            echo $this->Form->input('Info.avatar', array('class' => 'form-control', 'div' => 'form-group', 'value' => $user['Info']['avatar'], 'label' => __d('sweet_forum', 'Avatar')));
            echo $this->Form->submit(__d('sweet_forum', 'Update'), array('class' => 'btn btn-primary'));
            echo $this->Session->flash();
            echo $this->Form->end();
        ?>
    </div>
</div>
