<div class='row'>
    <div class='col-md-12'>
        <div class="page-header">
            <h3><?= __d('sweet_forum', 'Settings'); ?></h3>
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <?php
            echo $this->Session->flash();
        
            $langs = array(
                'eng' => 'English',
                'rus' => 'Русский'
            );
        
            echo $this->Form->create('Setting',
                array(
                    'class' => 'form',
                    'inputDefaults' => array(                            
                        'div' => 'form-group',
                        'class' => 'form-control'
                    )
                )
            );
            echo $this->Form->input('Setting.lang', array('options' => $langs, 'selected' => $settings['Setting']['lang'], 'label' => __d('sweet_forum', 'Language')));
            echo $this->Form->submit(__d('sweet_forum', 'Update'), array('class' => 'btn btn-primary'));
            echo $this->Form->end();
        ?>
    </div>
</div>