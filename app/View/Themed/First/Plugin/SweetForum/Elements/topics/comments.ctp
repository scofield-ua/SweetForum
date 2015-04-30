<div class='col-md-12'>
<?php
if(!empty($find['Comment'])) {
    $comments_sort_selected = array_key_exists('comments', $this->request->query) ? (int) $this->request->query['comments'] : 1;
    if($comments_sort_selected > 2) $comments_sort_selected = 2;
    $comments_sort_items = array(1 => __d("sweet_forum", "From old to new"), 2 => __d("sweet_forum", "From new to old"));
    
    echo "<h4 id='comments'>".__d("sweet_forum", "Comments");
    echo "<select class='change-comments-sorting'>";
    foreach($comments_sort_items as $index => $item) {
        $selected = $comments_sort_selected == $index ? "selected" : "";
        
        echo "<option value='{$index}' {$selected}>{$item}</option>";
    }
    
    echo "</select></h4>";
    
    echo "<ul class='comments clearfix'>";
    foreach($find['Comment'] as $comment) {
        $modified_str = "";
        if((bool) $comment['Info']['is_modified']) $modified_str = "(".__d("sweet_forum", "changed").": ".mb_strtolower($this->PrettyTime->pretty($comment['modified'])).")";

        echo "
            <li class='item padding-bottom15 clearfix' id='c-".$comment['hash_id']."'>
                <div class='col-md-12 comment-author-time'>
                    <p>
                        ".$this->Html->image($comment['Creator']['Info']['avatar'].'?s=20', array('class' => 'margin-right5'))."
                        <strong>".$this->Html->link($comment['Creator']['Info']['name'], SWEET_FORUM_BASE_URL.'u/'.$comment['Creator']['Info']['username'])."</strong> &middot;
                        <small>
                            <time datetime='".$comment['created']."'>
                                ".$this->Html->link(mb_strtolower($this->PrettyTime->pretty($comment['created'])), SWEET_FORUM_BASE_URL.'topic/'.$find['Topic']['url'].'#c-'.$comment['hash_id'], array('title' => __d("sweet_forum", "Comment link")))."
                                {$modified_str}
                            </time>
                        </small>
                    </p>
                </div>
                <div class='col-md-12 comment-text'>
                    ".TopicText::processText($comment['Info']['text'], array("cache_options" => array("duration" => "sf_default")))."
                </div>
        ";
        
        // buttons
        echo "<div class='col-md-12 text-right buttons'>";
        if($logged_in) {
            if($user_data['User']['id'] == $comment['Creator']['id']) {            
                echo $this->Html->link('<span class="glyphicon glyphicon glyphicon-pencil"></span>', SWEET_FORUM_BASE_URL.'comments/edit/'.$comment['hash_id'], array('title' => __d("sweet_forum", "Edit comment"), 'escape' => false, 'class' => 'btn btn-default btn-xs margin-right5')); // edit button
            }
            if($comment['Creator']['id'] != $user_data['User']['id']) {
                echo $this->Html->link('<span class="glyphicon glyphicon-flag"></span>', SWEET_FORUM_BASE_URL.'comment_activities/mark/'.$comment['hash_id'], array('title' => __d("sweet_forum", "Report this comment"), 'escape' => false, 'class' => 'btn btn-default btn-xs complaint margin-right5')); // mark button
            }
            echo $this->Html->link(__d("sweet_forum", "Reply"), SWEET_FORUM_BASE_URL.'topic/'.$find['Topic']['url'].'#c-'.$comment['hash_id'], array('class' => 'btn btn-default btn-xs reply', 'data-to' => $comment['hash_id'], 'data-reply-text' => __d("sweet_forum", "Reply"), "data-close-text" => __d("sweet_forum", "Close"), "data-clicked" => "false"));                    
        }        
        echo $this->element('parts/like', array('type' => 1, 'for' => $comment['hash_id'], 'likers' => $comment['Like'], 'button_size' => 'btn-xs'));
        echo "</div>";
        
        echo "<div class='clearfix'></div>";
        
        if(!empty($comment['Answer'])) { // replies for comment
            echo "<ol class='answers clearfix'>";
            foreach($comment['Answer'] as $answer) {
                $modified_str = "";
                if((bool) $answer['Info']['is_modified']) $modified_str .= " (".__d("sweet_forum", "changed").": ".mb_strtolower($this->PrettyTime->pretty($comment['modified'])).")";

                $right = $this->Html->link(trim($answer['Creator']['Info']['name']).", ".mb_strtolower($this->PrettyTime->pretty($answer['created'])).$modified_str, SWEET_FORUM_BASE_URL.'topic/'.$find['Topic']['url'].'#c-'.$answer['hash_id'], array('class' => 'link', 'escape' => false));

                echo "
                    <li class='answer-item clearfix' id='c-".$answer['hash_id']."'>
                        <div class='col-md-12 comment-author-time'>
                            <p>
                                ".$this->Html->image($answer['Creator']['Info']['avatar'].'?s=20', array('class' => 'margin-right5'))."
                                <strong>".$this->Html->link($answer['Creator']['Info']['name'], SWEET_FORUM_BASE_URL.'u/'.$answer['Creator']['Info']['username'])."</strong> &middot;
                                <small>
                                    <time datetime='".$comment['created']."'>
                                        ".$this->Html->link(mb_strtolower($this->PrettyTime->pretty($answer['created'])), SWEET_FORUM_BASE_URL.'topic/'.$find['Topic']['url'].'#c-'.$answer['hash_id'], array('title' => __d("sweet_forum", "Comment link")))."
                                        {$modified_str}
                                    </time>
                                </small>
                            </p>
                        </div>
                        <div class='col-md-12 comment-text'>
                            ".TopicText::processText($answer['Info']['text'], array("cache_options" => array("duration" => "sf_default")))."
                        </div>                        
                ";
                
                echo "<div class='col-md-12 text-right buttons'>";
                if($logged_in) {
                    if($user_data['User']['id'] == $answer['Creator']['id']) {
                        echo $this->Html->link('<span class="glyphicon glyphicon glyphicon-pencil"></span>', SWEET_FORUM_BASE_URL.'comments/edit/'.$answer['hash_id'], array('title' => __d("sweet_forum", "Edit comment"), 'escape' => false, 'class' => 'btn btn-default btn-xs margin-right5')); // edit button
                    }
                    if($answer['Creator']['id'] != $user_data['User']['id']) {
                        echo $this->Html->link('<span class="glyphicon glyphicon-flag"></span>', SWEET_FORUM_BASE_URL.'comment_activities/mark/'.$answer['hash_id'], array('title' => __d("sweet_forum", "Report this comment"), 'escape' => false, 'class' => 'btn btn-default btn-xs complaint margin-right5')); // mark button
                    }
                    echo $this->Html->link(__d("sweet_forum", "Reply"), SWEET_FORUM_BASE_URL.'topic/'.$find['Topic']['url'].'#c-'.$answer['hash_id'], array('class' => 'btn btn-default btn-xs reply', 'data-to' => $comment['hash_id'], 'data-reply-text' => __d("sweet_forum", "Reply"), "data-close-text" => __d("sweet_forum", "Close"), "data-clicked" => "false"));
                }
                echo $this->element('parts/like', array('type' => 1, 'for' => $answer['hash_id'], 'likers' => $answer['Like'], 'button_size' => 'btn-xs'));
                echo "</div>";
                
                echo "</li>";
            }
            echo "</ol>";
        }
        
        echo "</li>";
    }
    echo "</ul>";
}
?>
</div>