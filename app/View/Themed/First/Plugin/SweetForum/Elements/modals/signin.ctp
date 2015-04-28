<div class="modal fade" id="signin-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?= __d("sweet_forum", "Sign In"); ?> \ <?= $this->Html->link(__d("sweet_forum", "Sign Up"), '/users/signup'); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                    echo $this->Form->create('User', array('class' => 'form', 'url' => SWEET_FORUM_BASE_URL.'users/signin'));
                    echo $this->Form->input('User.email', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Email")));
                    echo $this->Form->input('User.password', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Password")));
                    echo $this->Form->hidden('User.back_url', array('value' => $current_url));
                    echo $this->Form->submit(__d("sweet_forum", "Sign In"), array('class' => 'btn btn-primary'));
                    echo $this->Form->end();
                ?>
            </div>            
        </div>
    </div>
</div>