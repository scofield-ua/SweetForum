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
        <?= $this->element('profile/nav', array('active' => 0)); ?>

        <h3 class='margin-top0'><?= __d("sweet_forum", "Your topics")." (".__d("sweet_forum", "last")." 10)"; ?></h3>
        <?php
            if(!empty($topics)) {
                echo "<ul class='list-unstyled'>";
                foreach($topics as $t) {
                    $date = $t['Topic']['created'];
                    $pretty = $this->PrettyTime->pretty($date);

                    echo "
                        <li class='margin-bottom5 padding0 clearfix'>
                            <div class='col-md-2 text-center'>
                                <p class='text-muted'><time datetime='{$date}'>{$pretty}</time></p>
                            </div>
                            <div class='col-md-10'>
                                ".$this->Html->link($t['Topic']['name'], SWEET_FORUM_BASE_URL.'topic/'.$t['Topic']['url'], array('escape' => false))."
                            </div>
                        </li>
                    ";
                }
                echo "<div class='clear'></div></ul>";
            } else {
                echo "<p class='nothing-found'>".__d("sweet_forum", "Nothing yet")."</p>";
            }
        ?>

        <h3><?= __d("sweet_forum", "Your comments")." (".__d("sweet_forum", "last")." 10)"; ?></h3>
        <?php
            if(!empty($comments)) {
                echo "<ul class='list-unstyled'>";
                $show_not_found = true;
                foreach($comments as $c) {
                    if($c['Topic']['status'] == "") $c['Topic']['status'] = -1;
                    if($c['Topic']['status'] >= 0) {
                        $show_not_found = false;

                        $date = $c['Comment']['created'];
                        $pretty = $this->PrettyTime->pretty($date);

                        echo "
                            <li class='margin-bottom5 padding0 clearfix'>
                                <div class='col-md-2 text-center'>
                                    <p class='text-muted'><time datetime='{$date}'>{$pretty}</time></p>
                                </div>
                                <div class='col-md-10'>
                                    ".$this->Html->link(__d("sweet_forum", "In topic").': <strong>&laquo;'.h($c['Topic']['name']).'&raquo;', SWEET_FORUM_BASE_URL.'topic/'.$c['Topic']['url'].'#c-'.$c['Comment']['hash_id'], array('escape' => false))."</strong>
                                </div>
                            </li>
                        ";
                    }
                }
                if($show_not_found) echo "<p class='nothing-found'>".__d("sweet_forum", "Nothing yet")."</p>";
                echo "<div class='clear'></div></ul>";

            } else {
                echo "<p class='nothing-found'>".__d("sweet_forum", "Nothing yet")."</p>";
            }
        ?>
    </div>
</div>
<?php
    if(isset($need_to_add_password)) {
        echo $this->element('modals/password-complete');
        $special_min_js[] = 'sweet_forum/First/js/users/password.js';
        $special_min_js[] = 'sweet_forum/First/js/users/password-complete.js';
    }
    $special_min_js[] = 'sweet_forum/First/js/messages/unread.js';
    $this->set('special_min_js', $special_min_js);
?>