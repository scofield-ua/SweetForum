<div class='row'>
    <div class='col-md-4 col-md-offset-4'>
        <div class="page-header">
            <h1><?= __d("sweet_forum", "Forgot your password?"); ?></h1>
        </div>        
        
        <?php
            if(!$hide_form) {
                echo $this->Form->create('User', array('class' => 'form clearfix'));
                echo $this->Form->input('User.email', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Email")));
                echo $this->Form->submit(__d("sweet_forum", "Submit"), array('class' => 'btn btn-primary pull-left'));
                echo $this->Html->link("<strong>".__d("sweet_forum", "Sign In")."</strong>", SWEET_FORUM_BASE_URL."users/signin", array("class" => "pull-right", "escape" => false));
                echo $this->Form->end();
            }                
            
            echo $this->Session->flash();            
        ?>            
    </div>
</div>