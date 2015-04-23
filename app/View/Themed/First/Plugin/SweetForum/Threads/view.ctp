<div class='row'>
    <div class='col-md-12'>
        <div class="page-header col-md-12">
            <h2>
                <small><?= $thread_info['Thread']['name']; ?></small>
                <?php if($logged_in) echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span>', '/topics/add/'.$thread_info['Thread']['url'], array('class' => 'btn btn-primary btn-sm pull-right', 'title' => __d("sweet_forum", "Start new topic"), 'escape' => false)); ?> 
            </h2>            
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <?= $this->element('topics/output', array('topics' => $topics)); ?>
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <?= $this->element('parts/pagination'); ?>
    </div>
</div>