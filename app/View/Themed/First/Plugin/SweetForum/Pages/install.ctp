<div class='row'>
    <div class='col-md-6 col-md-offset-3'>
        <h3><?= __d('sweet_forum', 'Welcome on SweetForum installation page'); ?></h4>
        <p><?= __d('sweet_forum', 'First of all we need to create your first user. We will be using it for login into admin dashboard.'); ?></p>
        <p><?= __d('sweet_forum', 'Type your data in form below:'); ?></p>
        <div class="panel panel-primary">            
            <div class="panel-body">                
                <?php
                    echo $this->Form->create('Setting',
                        array(
                            'class' => 'form',
                            'inputDefaults' => array(
                                'div' => 'form-group',
                                'class' => 'form-control'
                            )
                        )
                    );
                    echo $this->Form->input('Info.name', array('label' => __d('sweet_forum', 'Name')));
                    echo $this->Form->input('User.email', array('label' => __d('sweet_forum', 'Email')));
                    echo "<div class='form-group rel'>";
                        echo $this->Form->input('User.password', array('class' => 'form-control password-field', 'div' => false, 'label' => __d("sweet_forum", "Password")));
                        echo "<span class='input-helper eye glyphicon glyphicon-eye-open'></span>";
                    echo "</div>";
                ?>
            </div>
        </div>
        
        <p><?= __d('sweet_forum', 'Next, lets create first thread for discussions on our forum'); ?></p>
        
        <div class="panel panel-primary">            
            <div class="panel-body">
                <?php                    
                    echo $this->Form->input('Thread.name', array('label' => __d('sweet_forum', 'Title')));
                    echo $this->Form->input('Thread.description', array('label' => __d('sweet_forum', 'Description'), 'type' => 'textarea', 'rows' => 2));                    
                ?>
            </div>
        </div>
        
        <p><?= __d('sweet_forum', 'That\'s it. Just click on Submit button and you will be redirected to admin dashboard'); ?></p>
        
        <hr>
        
        <?php
            echo $this->Form->submit(__d('sweet_forum', 'Submit'), array('class' => 'btn btn-primary btn-block'));
            echo $this->Form->end();
        ?>
    </div>
</div>

<?php
    $special_min_js[] = 'sweet_forum/First/js/users/password.js';
    $this->set('special_min_js', $special_min_js);
?>