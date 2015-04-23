<div class='row'>
    <div class="page-header col-md-12">
        <h1><?= __d("sweet_forum", "Conversations"); ?> &middot; <?= $current_page; ?></h1>
    </div>
</div>

<div class='row'>
    <div class='col-md-3 text-center'>
        <?= $this->element('messages/nav'); ?>
    </div>
    
    <!-- Conversations -->
    <div class='col-md-9 conversations'>
        <?php
            if(empty($conversations)) {
                echo "<p class='text-muted text-center'>".__d("sweet_forum", "Nothing yet")."</p>";
            } else {
                echo "<ul class='conversations'>";
                foreach($conversations as $c) {                    
                    $url = SWEET_FORUM_BASE_URL.'messages/view/'.$c['ToUser']['hash_id'];
                    $text = "<em>".__d("sweet_forum", "Show messages history")."</em>";
                    
                    echo "
                        <li class='item'>
                            <a href='{$url}'>
                                <img src='".$c['ToUserInfo']['avatar']."' align='left' />
                                <h5>".$c['ToUserInfo']['name']."</h5>
                                <div class='text'>
                                    <p>{$text}</p>
                                </div>                                
                                <div class='clearfix'></div>
                            </a>                            
                        </li>
                    ";
                }
                echo "</ul>";
            }
        ?>
    </div>
</div>

<?php
$special_min_js[] = 'sweet_forum/First/js/messages/conversations.js';
$this->set('special_min_js', $special_min_js);        
?>