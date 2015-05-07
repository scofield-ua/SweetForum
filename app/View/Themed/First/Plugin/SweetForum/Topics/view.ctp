<div class='row'>
    <div class='col-md-12'>
        <h1 class='topic-h1'><?= Sanitize::html($find['Topic']['name']); ?></h1>
    </div>
</div>

<div class='row padding-bottom15'>
    <div class='col-md-12'>
        <ul class='topic-info pull-left'>
            <li>
                <time datetime='<?= $topic_date; ?>'>
                    <?php
                        echo $this->PrettyTime->pretty($topic_date);
                        if((bool) $find['Topic']['is_modified']) {
                            echo " <span class='small glyphicon glyphicon-pencil' title='".__d("sweet_forum", "edited").": ".mb_strtolower($this->PrettyTime->pretty($find['Topic']['modified']))."'></span>";
                        }
                    ?>
                </time>
            </li>
            <li>&#8594;</li>
            <li><?= $this->Html->link($find['Thread']['name'], SWEET_FORUM_BASE_URL."threads/view/".$find['Thread']['url']); ?></li>
        </ul>
        <ul class='topic-author-buttons pull-right'>
            <?php
                if($owner) echo "<li>".$this->Html->link(__d("sweet_forum", "Edit"), SWEET_FORUM_BASE_URL.'topics/edit/'.$find['Topic']['url'], array('class' => 'btn btn-default btn-xs'))."</li>";
            ?>
        </ul>
    </div>
</div>

<div class='row'>
    <!-- Authour -->
    <div class='col-md-2 text-center'>
        <div class='col-md-12 padding-bottom15'>
            <?php
                echo "<p>".$this->Html->link($this->Html->image($find['Creator']['Info']['avatar']."?s=75"), SWEET_FORUM_BASE_URL.'u/'.$find['Creator']['Info']['username'], array('escape' => false))."</p>";
                echo "<p><strong>".$find['Creator']['Info']['name']."</strong></p>";
            ?>
        </div>
        <div class='col-md-12 padding-bottom15'>
            <?= $this->element('parts/like', array('type' => 0, 'for' => $find['Topic']['url'], 'likers' => $find['Like'])); ?>
        </div>
        <div class='col-md-12 padding-bottom15'>
            <?php #$this->element('parts/social-share'); ?>
        </div>
        <div class='col-md-12'>
            <div class="btn-group">
                <?php
                    if($logged_in) {
                        if($user_data['User']['id'] != $find['Topic']['user_id'])  {
                            echo $this->Html->link('<span class="glyphicon glyphicon-flag"></span>', SWEET_FORUM_BASE_URL.'topic_activities/mark/'.$find['Topic']['url'], array('escape' => false, 'class' => 'btn btn-default btn-xs complaint', 'title' => __d("sweet_forum", "Report this topic")));                        }
                    }
                ?>
            </div>
        </div>
    </div>

    <div class='col-md-10 topic-content'>
        <?= $find['Topic']['text']; ?>
    </div>
</div>


<div class='row topic-comments'>    
    <?= $this->element('topics/comments'); ?>
</div>

<div class='row add-comment'>
    <div class='col-md-12'>
        <?php if($logged_in) : ?>
        <div class='row for-reply hide'>            
            <div class='col-md-12'>
                <div class='panel panel-default'>
                    <div class="panel-heading"><strong><?= __d("sweet_forum", "Comment to reply"); ?></strong></div>
                    <div class="panel-body">                        
                    </div>
                </div>
            </div>
        </div>        
        
        <div class='row'>
            <div class='col-md-12'>
                <h4><?= __d("sweet_forum", "Add new comment"); ?></h4>
                <?php
                    $ava = $this->Html->image($user_data['Info']['avatar']."?s=34", array("align" => "left", "class" => "pull-right"));
        
                    echo $this->Form->create('Comment',
                        array(
                            'inputDefaults' => array('label' => false),
                            'class' => 'form clearfix'
                        )
                    );
                    echo $this->Form->input('Info.text', array('placeholder' => __d("sweet_forum", "Comment text"), 'type' => 'textarea', 'class' => 'form-control', 'div' => 'form-group', 'rows' => 4));
                    echo $this->Form->hidden('answer_to');
                    echo $this->Form->submit(__d("sweet_forum", "Post comment"), array('class' => 'btn btn-primary margin-right15 pull-left', 'escape' => false));
                    echo $this->Form->button(__d("sweet_forum", "Cancel"), array('class' => 'btn btn-default margin-right15 pull-left reply-cancel hide', 'escape' => false)).$ava;
                    echo $this->Form->end();
                    echo $this->Session->flash();
                ?>
            </div>
        </div>        
        <?php endif; ?>
    </div>
</div>