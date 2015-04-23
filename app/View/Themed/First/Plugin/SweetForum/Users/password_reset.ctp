<div class='row'>
    <div class='col-md-6 col-md-offset-3'>
        <div class="page-header">
            <h1><?= __d("sweet_forum", "Password reset"); ?></h1>
        </div>
            
        <?php
            if(!$hide_form) {
                echo $this->Form->create('User', array('class' => 'form'));
                echo "<div class='form-group rel'>";
                    echo $this->Form->input('User.password', array('class' => 'form-control password-field', 'div' => false, 'label' => __d("sweet_forum", "Type your new password")));
                    echo "<span class='input-helper eye glyphicon glyphicon-eye-open'></span>";
                echo "</div>";
                echo $this->Form->submit(__d("sweet_forum", "Reset"), array('class' => 'btn btn-primary pull-left'));
                echo $this->Html->link(__d("sweet_forum", "Sign In"), "/users/signin", array("class" => "pull-right"));
                echo $this->Form->end();
            }
            
            echo "<div class='clearfix'></div>";
            
            echo $this->Session->flash();
        ?>            
    </div>
</div>
<?php
$special_min_js[] = 'sweet_forum/First/js/users/password.js';
$this->set('special_min_js', $special_min_js);