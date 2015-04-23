<?php
$not_my_page = ($logged_in && $user['User']['id'] != $user_data['User']['id']);
?>

<div class='row'>
    <div class='col-md-12 col-sm-12 col-xs-12'>
        <div class="page-header"><h1><small><?= $user['Info']['name']; ?></small></h1></div>
    </div>
</div>

<div class='row'>
    <div class='col-md-2 col-sm-3 col-xs-6 text-center'>
        <?php
            echo "<p>".$this->Html->image($user['Info']['avatar']."?s=200")."</p>";
            
            if($not_my_page)  {
                echo "<p>".$this->Html->link("<span class='glyphicon glyphicon-envelope'></span> ".__d("sweet_forum", "Send message"), '#', array('class' => 'btn btn-primary btn-sm btn-block send-message-to-user', 'escape' => false, 'data-target' => '#user-profile-view-message-modal'))."</p>";
                echo "<p>".$this->Html->link("<span class='glyphicon glyphicon-flag'></span> ".__d("sweet_forum", "Report user"), '#', array('class' => 'btn btn-warning btn-sm btn-block report-user', 'escape' => false, 'data-target' => '#user-profile-view-report-modal'))."</p>";
            }
        ?>
    </div>
    <div class='col-md-10 col-sm-9 col-xs-6'>
        <?php
            echo "<p><strong>".__d('sweet_forum', 'Name').":</strong> ".$user['Info']['name']."";
            echo "<p><strong>".__d('sweet_forum', 'Topics').":</strong> {$topics}";
            echo "<p><strong>".__d('sweet_forum', 'Comments').":</strong> {$comments}";
            if($user['Info']['social'] !== null && (bool) $user['Privacy']['show_social_link']) {
                echo "<p><strong>".__d("sweet_forum", "Social network").":</strong> ".$this->Html->link(__d("sweet_forum", "Page"), $user['Info']['social'], array('target' => '_blank', 'rel' => 'nofollow'))."</p>";
            }            
        ?>
    </div>
</div>
<?php
if($not_my_page)  {
    echo $this->element('modals/user-profile-view', array('user_name' => $user['Info']['name'], 'to_user' => $user['User']['hash_id']));
    $special_min_js[] = 'sweet_forum/First/js/users/view.js';
    $this->set('special_min_js', $special_min_js);
}
?>