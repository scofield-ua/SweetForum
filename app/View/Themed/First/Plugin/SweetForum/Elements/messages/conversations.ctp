<?php
// default params
$hide_buttons = isset($hide_buttons) ? $hide_buttons : false;
$recovery_button = isset($recovery_button) ? $recovery_button : false;
$is_other = isset($is_other) ? $is_other : false; // is "Other" page

if(empty($conversations)) {
    echo "<p class='text-muted text-center'>".__d("sweet_forum", "Nothing yet")."</p>";
} else {
    echo "<ul class='conversations'>";
    foreach($conversations as $c) {
        if(!$is_other) {
            $url = SWEET_FORUM_BASE_URL.'messages/view/'.$c['Creator']['hash_id'];
        } else {
            $url = SWEET_FORUM_BASE_URL.'messages/view/'.$c['LastMessageToUser']['hash_id'];
        }
        $class = '';
        if($c['LastMessage']['from_user_id'] != $user_data['User']['id']) { // if last message not from you
            $class = $c['LastMessage']['checked'] == 0 ? 'not-checked' : '';
        }
        $text = htmlspecialchars(strip_tags($c['LastMessage']['text']));
        
        $buttons = "";
        if(!$hide_buttons) {
            if(!$recovery_button) {
                $buttons = "<span class='glyphicon glyphicon-remove opt-button remove' data-from='".$c['Creator']['hash_id']."' title='".__d("sweet_forum", "Remove conversation")."'></span>";
            } else {
                $buttons = "<span class='glyphicon glyphicon-share-alt opt-button recovery' data-from='".$c['Creator']['hash_id']."' title='".__d("sweet_forum", "Recovery conversation")."'></span>";
            }
        }
        
        echo "
            <li class='{$class} item'>
                <a href='{$url}'>
                    <img src='".$c['CreatorInfo']['avatar']."' align='left' />
                    <h5>".$c['CreatorInfo']['name']."</h5>
                    <div class='text'>
                        <p>{$text}</p>
                    </div>                                
                    <div class='clearfix'></div>
                </a>
                {$buttons}
            </li>
        ";
    }
    echo "</ul>";
}