<div class="well well-sm well-nav">
    <ul class="nav nav-pills nav-stacked small sf-dashboard-nav">
        <?php
            $current_nav = isset($current_nav) ? $current_nav : "";
            $items = array(
                SWEET_FORUM_BASE_URL."admin" => "<span class='glyphicon glyphicon-dashboard'></span> &nbsp; ".__d("sweet_forum", "Dashboard"),
                SWEET_FORUM_BASE_URL."users" => "<span class='glyphicon glyphicon-user'></span> &nbsp; ".__d("sweet_forum", "Users"),
                SWEET_FORUM_BASE_URL."threads" => "<span class='glyphicon glyphicon-th-list'></span> &nbsp; ".__d("sweet_forum", "Threads"),
                SWEET_FORUM_BASE_URL."topics" => "<span class='glyphicon glyphicon-align-justify'></span> &nbsp; ".__d("sweet_forum", "Topics"),
            );
            $subitems = array(
                SWEET_FORUM_BASE_URL."admin" => array(
                    SWEET_FORUM_BASE_URL."admin" => __d("sweet_forum", "Dashboard"),
                    SWEET_FORUM_BASE_URL."admin/settings/index" => __d("sweet_forum", "Settings"),
                ),
                SWEET_FORUM_BASE_URL."users" => array(
                    SWEET_FORUM_BASE_URL."admin/users/index" => __d("sweet_forum", "All users"),
                    SWEET_FORUM_BASE_URL."admin/users/banned" => __d("sweet_forum", "Banned users"),
                    SWEET_FORUM_BASE_URL."admin/users/reports" => __d("sweet_forum", "Users reports"),
                ),
                SWEET_FORUM_BASE_URL."threads" => array(
                    SWEET_FORUM_BASE_URL."admin/threads/index" => __d("sweet_forum", "All threads"),
                ),
                SWEET_FORUM_BASE_URL."topics" => array(
                    SWEET_FORUM_BASE_URL."admin/topics/index" => __d("sweet_forum", "All topics"),
                )
            );

            echo $this->element('parts/nav_output', array('items' => $items, 'subitems' => $subitems, 'current_nav' => $current_nav));
        ?>
    </ul>
</div>