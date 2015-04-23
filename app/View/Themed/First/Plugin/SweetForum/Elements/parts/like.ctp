<?php
    // check for user like
	
    $liked = $show = false;
    if($logged_in && isset($likers)) {
        if(count($likers) > 0) {
            $merge = array();
            foreach($likers as $like) $merge = array_merge_recursive($merge, $like);
            $liked = in_array($user_data['User']['id'], (array) $merge['user_id']);

            $show = true;
        }
    } else if(isset($likers)) {
        if(count($likers) > 0) $show = true;
    }
    
    $url = $logged_in ? Router::url(SWEET_FORUM_BASE_URL."likes/add") : "";    
    $likes = isset($likers) ? count($likers) : "";
    
    $button_like_class = $liked ? "btn-primary" : "btn-default";
    $button_size = !isset($button_size) ? "btn-sm" : $button_size;
?>

<button type="button" class="btn <?= $button_like_class." ".$button_size; ?> like" data-url='<?= $url; ?>' data-type="<?= $type; ?>" data-for="<?= $for; ?>" data-likes="<?= $likes; ?>">
    <span class='glyphicon glyphicon-thumbs-up'></span>
    <strong class='count'><?= $likes == 0 ? "" : $likes; ?></strong>
</button>