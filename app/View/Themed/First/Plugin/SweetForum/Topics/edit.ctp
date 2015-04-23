<div class='col-md-12'>
    <div class='row'>
        <div class='row'>
            <div class='col-md-12'>
                <div class="page-header">
                    <h1><small><?= $this->Html->link('&#8592; '.__d("sweet_forum", "Topic page"), '/topic/'.$url, array('escape' => false)); ?></small></h1>
                </div>
            </div>
        </div>
        
        <div class='row'>
            <div class="col-md-12 margin-top15">
                <?php
                    echo $this->Form->create('Topic', array('class' => 'form add-topic'));
                    echo $this->Form->input('name', array('label' => __d("sweet_forum", "Topic title"), 'class' => 'form-control', 'div' => 'form-group', 'value' => htmlspecialchars_decode($find['Topic']['name'])))."<br/>";
                    echo $this->Form->input('text', array('label' => __d("sweet_forum", "Topic content"), 'class' => 'form-control height-400', 'div' => 'form-group', 'value' => $find['Topic']['text']));            
                    echo '<span class="label label-default">'.$this->Html->link(__d("sweet_forum", "What tags you can use"), "#", array("class" => "color-white wtycu")).'</span>';
                    echo $this->Form->submit(__d("sweet_forum", "Update"), array('class' => 'btn btn-primary margin-top15 pull-left margin-right15', 'name' => 'update'));
                    echo $this->Form->submit(__d("sweet_forum", "Preview"), array('class' => 'btn btn-default margin-top15 pull-left margin-right15', 'name' => 'preview'));
                    echo $this->Form->submit(__d("sweet_forum", "Delete"), array('class' => 'btn btn-danger margin-top15 pull-left', 'name' => 'delete'));
                    echo $this->Session->flash();                
                ?>
            </div>
        </div>
    </div>
</div>

<?php
    echo $this->element('modals/what-tags-to-use');
    $special_min_js[] = 'sweet_forum/First/js/other/what-tags-to-use.js';
    $this->set('special_min_js', $special_min_js);
?>