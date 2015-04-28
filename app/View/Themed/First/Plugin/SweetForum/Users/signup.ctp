<div class='row'>
    <div class='col-md-6 col-md-offset-3'>
        <div class="page-header">
            <h1><?= __d("sweet_forum", "Sign Up"); ?></h1>
        </div>
            
        <?php
            echo $this->Form->create('User', array('class' => 'form'));
            echo $this->Form->input('Info.name', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Name")));
            echo $this->Form->input('User.email', array('class' => 'form-control', 'div' => 'form-group', 'label' => __d("sweet_forum", "Email")));
            echo "<div class='form-group rel'>";
                echo $this->Form->input('User.password', array('class' => 'form-control password-field', 'div' => false, 'label' => __d("sweet_forum", "Password")));
                echo "<span class='input-helper eye glyphicon glyphicon-eye-open'></span>";
            echo "</div>";
            echo $this->Form->submit(__d("sweet_forum", "Sign Up"), array('class' => 'btn btn-primary'));
            echo $this->Form->end();            
        ?>
        
        <p class='margin-top15'><?= __d("sweet_forum", "Have an account?")." ".$this->Html->link(__d("sweet_forum", "Sign In"), SWEET_FORUM_BASE_URL."users/signin?back=".$current_back); ?></p>
    </div>
</div>
<?php
$special_min_js[] = 'sweet_forum/First/js/users/password.js';
$this->set('special_min_js', $special_min_js);
?>