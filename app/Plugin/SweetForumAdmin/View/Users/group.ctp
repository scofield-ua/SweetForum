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
            echo $this->Form->input('User.role', array('class' => 'form-control', 'div' => 'form-group', 'options' => $groups, 'selected' => $user['User']['role'], 'label' => __d('sweet_forum', 'Group')));
            echo $this->Form->submit(__d('sweet_forum', 'Update'), array('class' => 'btn btn-primary'));
            echo $this->Session->flash();
            echo $this->Form->end();
        ?>
    </div>
</div>
