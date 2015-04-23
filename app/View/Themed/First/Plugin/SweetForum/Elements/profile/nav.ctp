<div class='row'>
    <div class='col-md-12'>
        <?php
            $active = isset($active) ? $active : 0;
            
            $menus = array(
                0 => array(
                    'title' => __d("sweet_forum", "Activity"),
                    'url' => SWEET_FORUM_BASE_URL."users/profile"
                ),
                1 => array(
                    'title' => __d("sweet_forum", "Notifications"),
                    'url' => SWEET_FORUM_BASE_URL."users/notifications"
                )
            );
        ?>
        <ul class="nav nav-tabs margin-bottom15">
            <?php
                foreach($menus as $index => $menu) {
                    $class = $index == $active ? "class='active'" : "";
                    
                    echo "<li $class>".$this->Html->link($menu['title'], $menu['url'])."</li>";
                }
            ?>    
        </ul>
    </div>
</div>