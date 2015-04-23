<div class='row'>
    <div class='col-md-12'>
        <div class="page-header"><h1><small><?= $page_title; ?></small></h1></div>
    </div>
</div>

<div class='row profile-page'>
    <div class='col-md-2 text-center'>
        <?= $this->element('profile/default-left-part'); ?>
        <div class='clearfix'></div>
    </div>

    <div class='col-md-10 summary'>
        <?= $this->element('profile/nav', array('active' => 1)); ?>

        <div class='row'>
            <div class='col-md-12'>
                <?php
                    $menus = array(
                        'topics' => array(
                            'title' => __d("sweet_forum", "Topics"),
                            'url' => '?type=topics',
                        ),
                        'comments' => array(
                            'title' => __d("sweet_forum", "Comments"),
                            'url' => '?type=comments'
                        ),
                    );
                ?>
                <ul class="nav nav-pills nav-justified">
                    <?php
                        foreach($menus as $type => $menu) {
                            $class = $current_type == $type ? "class='active'" : "";

                            echo "<li {$class}>".$this->Html->link($menu['title'], $menu['url'])."</li>";
                        }
                    ?>
                </ul>

                <div class='row'>
                    <div class='col-md-6'>
                        <h3><span class='glyphicon glyphicon-align-justify'></span></h3>

                        <?php
                            if(isset($left) && !empty($left)) {
                                switch($current_type) {
                                    case 'topics' :
                                        echo "<ul class='list-unstyled notif-items'>";
                                        foreach($left as $item) {
                                            $user_link = $this->Html->link($item['UserInfo']['name'], SWEET_FORUM_BASE_URL.'u/'.$item['UserInfo']['username']);
                                            $topic_link = $this->Html->link($item['Topic']['name'], SWEET_FORUM_BASE_URL.'topic/'.$item['Topic']['url'].'#c-'.$item['Comment']['hash_id']);
                                            
                                            echo "
                                                <li>
                                                    <small><time>".$this->PrettyTime->pretty($item['Comment']['created'])."</time></small>
                                                    <p>
                                                        ".__d("sweet_forum", "You have comment from %s in %s", array($user_link, $topic_link))."
                                                    </p>
                                                </li>
                                            ";
                                        }
                                        echo "</ul>";
                                    break;
                                    case 'comments' :
                                        echo "<ul class='list-unstyled notif-items'>";
                                        foreach($left as $item) {
                                            $answer = $this->Html->link(__d("sweet_forum", "answer"), SWEET_FORUM_BASE_URL.'topic/'.$item['Topic']['url'].'#c-'.$item['Comment']['hash_id']);
                                            $user_link = $this->Html->link($item['Creator']['Info']['name'], SWEET_FORUM_BASE_URL.'u/'.$item['Creator']['Info']['username']);
                                            $topic_link = $this->Html->link($item['Topic']['name'], SWEET_FORUM_BASE_URL.'topic/'.$item['Topic']['url']);
                                            
                                            echo "
                                                <li>
                                                    <small><time>".$this->PrettyTime->pretty($item['Comment']['created'])."</time></small>
                                                    <p>
                                                        ".__d("sweet_forum", "You got %s from %s for comment in %s", array($answer, $user_link, $topic_link))."
                                                    </p>
                                                </li>
                                            ";
                                        }
                                        echo "</ul>";
                                    break;
                                }
                            } else {
                                echo "<p class='text-muted'>".__d("sweet_forum", "Nothing yet")."</p>";
                            }
                        ?>
                    </div>
                    <div class='col-md-6'>
                        <h3><span class='glyphicon glyphicon-heart'></span></h3>

                        <?php
                            if(isset($right) && !empty($right)) {
                                switch($current_type) {
                                    case 'topics' :
                                        echo "<ul class='list-unstyled notif-items'>";
                                        foreach($right as $item) {
                                            $user_link = $this->Html->link($item['TopicLikesNotification']['from_user_name'], SWEET_FORUM_BASE_URL.'u/'.$item['TopicLikesNotification']['from_user_username']);
                                            $topic_link = $this->Html->link($item['TopicLikesNotification']['topic_name'], SWEET_FORUM_BASE_URL.'topic/'.$item['TopicLikesNotification']['topic_url']);
                                            
                                            echo "
                                                <li>
                                                    <small><time>".$this->PrettyTime->pretty($item['TopicLikesNotification']['created'])."</time></small>
                                                    <p>
                                                        ".__d("sweet_forum", "You got like from %s for topic %s", array($user_link, $topic_link))."
                                                    </p>
                                                </li>
                                            ";
                                        }
                                        echo "</ul>";
                                    break;
                                    case 'comments' :
                                        echo "<ul class='list-unstyled notif-items'>";
                                        foreach($right as $item) {
                                            $comment_link = $this->Html->link(__d("sweet_forum", "comment"), SWEET_FORUM_BASE_URL.'topic/'.$item['Topic']['url'].'#c-'.$item['CommentLikesNotification']['comment_hash_id'], array('title' => $item['Topic']['name']));
                                            $user_link = $this->Html->link($item['CommentLikesNotification']['from_user_name'], SWEET_FORUM_BASE_URL.'u/'.$item['CommentLikesNotification']['from_user_username']);
                                            
                                            echo "
                                                <li>
                                                    <small><time>".$this->PrettyTime->pretty($item['CommentLikesNotification']['created'])."</time></small>
                                                    <p>
                                                        ".__d("sweet_forum", "Your %s got liked by %s", array($comment_link, $user_link))."                                                        
                                                    </p>
                                                </li>
                                            ";
                                        }
                                        echo "</ul>";
                                    break;
                                }
                            } else {
                                echo "<p class='text-muted'>".__d("sweet_forum", "Nothing yet")."</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $special_min_js[] = 'sweet_forum/First/js/messages/unread.js';
    $this->set('special_min_js', $special_min_js);
?>