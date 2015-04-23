<div class='row'>
    <div class="page-header col-md-12 text-center">
        <h1>
            <small>
            <?=
                $this->Html->link("<span class='glyphicon glyphicon-chevron-left margin-left5'></span>", SWEET_FORUM_BASE_URL.'messages/index', array('escape' => false, 'title' => __d("sweet_forum", "back"), 'class' => 'message-header-back pull-left'))." ".
                $page_title;
            ?>
            </small>
            <div class="btn-group pull-right margin-right5">
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                    <span class='glyphicon glyphicon-cog'></span>
                </button>
                <ul class="dropdown-menu text-left pull-right" role="menu">
                    <li><?= $this->Html->link("<span class='glyphicon glyphicon-lock'></span> ".__d("sweet_forum", "Block user"), SWEET_FORUM_BASE_URL.'messages/block_user/'.$interlocutor['Creator']['hash_id'], array('escape' => false, 'class' => 'conv block-user')); ?></li>
                </ul>
            </div>
        </h1>        
    </div>
</div>

<div class='row'>
    <div class='col-md-8 col-md-offset-2 messages-output'>
        <?php
            if($current_page < $max_pages) {
                echo $this->Html->link("<span class='glyphicon glyphicon-time'></span> ".__d("sweet_forum", "Load older message"), $this->here."?page=".($current_page+1), array('class' => 'btn btn-default btn-xs btn-block margin-bottom15', 'escape' => false));
            }
        
            echo "<ul class='messages'>";
            foreach($messages as $message) {
                $your_message = ($message['Creator']['id'] == $user_data['User']['id']);
                
                switch((int) $message['Message']['status']) {
                    case 0 :
                        TopicText::$options = array(
                            "cache_options" => array("duration" => "default"),
                            "gallery" => array(
                                "class" => "venobox",
                                "gallery-id" => "venobox-".md5($message['Message']['id'].$message['CreatorInfo']['name'].$message['CreatorInfo']['avatar'])
                            ),
                            "not_load_iframes" => true
                        );
                        $text = TopicText::processText($message['Info']['text']);
                    break;
                    case -1 :
                        $text = "<p class='text-muted'><em>".__d("sweet_forum", "Message deleted")."</em></p>";
                    break;
                }
                
                $li_class = $your_message ? 'you' : '';
                
                $buttons = "";
                if($your_message) {
                    $buttons = '
                        <div class="btn-group edit-buttons pull-right">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">                                    
                                <span class="glyphicon glyphicon-cog"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    '.$this->Html->link('<span class="glyphicon glyphicon-trash"></span> '.__d('sweet_forum', 'Delete'), SWEET_FORUM_BASE_URL."messages/delete/".$message["Message"]["hash_id"], array('escape' => false, 'class' => 'edit-delete')).'
                                </li>
                                <li>
                                    '.$this->Html->link('<span class="glyphicon glyphicon-pencil"></span> '.__d('sweet_forum', 'Edit'), SWEET_FORUM_BASE_URL."messages/edit/".$message["Message"]["hash_id"], array('escape' => false, 'class' => 'edit-edit')).'                                    
                                </li>
                            </ul>
                        </div>
                    ';
                } else {
                    $buttons = '
                        <div class="btn-group edit-buttons pull-right">
                            <button type="button" class="btn btn-default btn-xs edit-hide">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </div>
                    ';
                }
                
                if($message['Message']['is_modified']) {
                    $buttons .= "<span class='glyphicon glyphicon-pencil pull-right gray' title='".__d('sweet_forum', 'edited').": ".$this->PrettyTime->pretty($message['Message']['modified'])."'></span>";
                }
                
                $ava = $this->Html->link($this->Html->image($message['CreatorInfo']['avatar'], array('class' => 'ava')), SWEET_FORUM_BASE_URL.'u/'.$message['Creator']['hash_id'], array('escape' => false));
                
                echo "
                    <li class='item {$li_class}'>
                        <div class='row'>
                            <div class='col-md-1'>
                                {$ava}
                            </div>
                            <div class='col-md-11'>
                                <div class='row info'>
                                    <div class='col-md-8 col-sm-8 col-xs-7'>
                                        <h5 class='author-name'>".$message['CreatorInfo']['name']."</h5>
                                    </div>
                                    <div class='col-md-4 col-sm-4 col-xs-5'>
                                        {$buttons}
                                        <p class='time text-right pull-right'>".$this->PrettyTime->pretty($message['Message']['created'])."</p>                                        
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-md-12'>{$text}</div>
                                </div>
                            </div>
                        </div>
                    </li>
                ";
            }
            echo "</ul>";
        ?>
    </div>
</div>
<div class='row margin-top15'>    
    <div class='col-md-8 col-md-offset-2'>
        <?php
            echo $this->Form->create('Message');
            echo $this->Form->input('Info.text', array('label' => false, 'div' => 'form-group', 'class' => 'form-control', 'placeholder' => __d('sweet_forum', 'Type your message here'), 'type' => 'textarea', 'error' => false));
            echo $this->Form->submit(__d("sweet_forum", "Submit"), array('class' => 'btn btn-primary'));
            echo $this->Session->flash();
            echo $this->Form->end();
            
            if($is_user_block_you) {
                echo '<div class="alert alert-danger margin-top15" role="alert">'.__d('sweet_forum', "You can't send message to this user, because you was blocked").' ^_^</div>';
            }
        ?>
    </div>
</div>