<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="collapse navbar-collapse">
            <div class="navbar-header">
                <?= $this->Html->link('<span class="glyphicon glyphicon-arrow-left"></span>', SWEET_FORUM_BASE_URL, array('class' => 'navbar-brand', 'title' => __d('sweet_forum', 'Forum homepage'), 'escape' => false)); ?>
                <?= $this->Html->link('SF Dashboard', SWEET_FORUM_BASE_URL.'admin', array('class' => 'navbar-brand')); ?>
            </div>
        </div>
    </div>
</nav>