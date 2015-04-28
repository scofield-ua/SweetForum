<div class='row'>
    <div class='col-md-6 col-md-offset-3'>
        <div class="page-header">
            <h1><?= __d("sweet_forum", "Sign In"); ?></h1>
        </div>
            
        <?php
            echo $this->Form->create('User', array('class' => 'form clearfix'));
            echo $this->Form->input('User.email', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Email")));
            echo $this->Form->input('User.password', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Password")));
            echo $this->Form->submit(__d("sweet_forum", "Sign In"), array('class' => 'btn btn-primary pull-left'));
            echo $this->Html->link("<strong>".__d("sweet_forum", "Forgot password?")."</strong>", SWEET_FORUM_BASE_URL."users/password", array("class" => "pull-right", "escape" => false));
            echo $this->Form->end();
        ?>
        
        <p class='margin-top15'><?= __d("sweet_forum", "Dont have an account?")." ".$this->Html->link(__d("sweet_forum", "Sign Up"), SWEET_FORUM_BASE_URL."users/signup?back=".$current_back); ?></p>
        
        <?php            
            if(isset($error_message)) echo '<div class="alert alert-danger">'.$error_message.'</div>';
        ?>            
    </div>
</div>