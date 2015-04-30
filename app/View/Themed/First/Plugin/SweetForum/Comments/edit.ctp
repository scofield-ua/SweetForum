<div class='row'>
    <div class='col-md-12'>
        <div class="page-header">
            <h1><?= __d("sweet_forum", "Comment edit"); ?></h1>
        </div>
    </div>
    
    <div class='col-md-12'>
        <p>
            <strong><?= __d("sweet_forum", "Topic"); ?>:</strong>
            <?= $this->Html->link($topic['Topic']['name'], SWEET_FORUM_BASE_URL.'topic/'.$topic['Topic']['url']); ?>
        </p>
        <?php
        echo $this->Form->create('Comment', array('class' => 'form'));
        echo $this->Form->input('Info.text', array('type' => 'textarea', 'value' => $find['Info']['text'], 'class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Comment text")));
        echo $this->Form->submit(__d("sweet_forum", "Update"), array('name' => 'update', 'class' => 'btn btn-primary pull-left margin-right15'));
        echo $this->Form->submit(__d("sweet_forum", "Delete"), array('name' => 'delete', 'class' => 'btn btn-danger'));
        echo $this->Form->end();
        echo $this->Session->flash();
        ?>
    </div>
</div>