<div class='row margin-top15'>
    <div class='col-md-12 padding-bottom15'>
        <h1 class='topic-h1'><?= $name; ?></h1>
    </div>
    
    <div class='col-md-2 text-center'>
        <?php
            echo $this->Html->image($user_data['Info']['avatar']."?s=75")."<br/>";
            echo "<strong class='margin-top15'>".$user_data['Info']['name']."</strong>";
        ?>
    </div>
    <div class='col-md-10 topic-content'>
        <?= $text; ?>        
    </div>
    <div class='col-md-12 margin-top15'>
        <button class='btn btn-primary btn-lg btn-block' onclick='window.history.back();'><?= __d("sweet_forum", "Back to editing"); ?></button>
    </div>
</div>

<?php echo $this->Html->script(array('/sweet_forum/First/js/topics/view')); ?>