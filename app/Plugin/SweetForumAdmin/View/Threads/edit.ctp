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
            echo $this->Form->create('Thread', array('class' => 'form'));
            echo $this->Form->input('Thread.name', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d('sweet_forum', 'Thread name'), 'value' => $thread['Thread']['name']));
            echo $this->Form->input('Thread.description', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d('sweet_forum', 'Description'), 'value' => $thread['Thread']['description']));
            echo $this->Form->input('Thread.url', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d('sweet_forum', 'URL'), 'value' => $thread['Thread']['url']));
            echo $this->Form->submit(__d('sweet_forum', 'Update'), array('class' => 'btn btn-primary'));
            echo $this->Session->flash();
            echo $this->Form->end();
        ?>
    </div>
</div>
