<div class='row'>
    <div class='col-md-9'>
        <div class="page-header">
            <h3><?= $page_title; ?></h3>
        </div>
    </div>
    <div class='col-md-3'>
        <?= $this->Html->link('+ '.__d('sweet_forum', 'Add new thread'), SWEET_FORUM_BASE_URL.'admin/threads/add', array('class' => 'btn btn-primary pull-right')); ?>
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <?php
            echo $this->Session->flash();
        
            if(empty($threads)) {
                echo $this->element('parts/nothing');
            } else {
                foreach($threads as $item) {
                    echo "
                        <div class='row'>
                            <div class='col-md-12'>
                                <strong>".$this->Html->link($item['Thread']['name'], SWEET_FORUM_BASE_URL.'threads/view/'.$item['Thread']['url'])."</strong>
                                <p class='text-muted'>".$item['Thread']['description']."</p>
                                <p>".$this->Html->link(__d('sweet_forum', 'Edit'), SWEET_FORUM_BASE_URL.'admin/threads/edit/'.$item['Thread']['id'], array('class' => 'btn btn-default btn-xs'))." ".$this->Html->link(__d('sweet_forum', 'Delete'), SWEET_FORUM_BASE_URL.'admin/threads/delete/'.$item['Thread']['id'], array('class' => 'btn btn-danger btn-xs', 'confirm' => __d('sweet_forum', 'Are you sure?')))."</p>
                                <hr>
                            </div>
                        </div>
                    ";
                }
            }
        ?>
    </div>
</div>