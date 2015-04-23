<div class="well well-sm well-nav">
    <ul class="nav nav-pills nav-stacked small sf-dashboard-nav">
        <?php
            $current_nav = isset($current_nav) ? $current_nav : "";
            $items = array(
                "/admin" => "<span class='glyphicon glyphicon-dashboard'></span> &nbsp; ".__d("sweet_forum", "Dashboard"),
                "/users" => "<span class='glyphicon glyphicon-user'></span> &nbsp; ".__d("sweet_forum", "Users"),
                "/threads" => "<span class='glyphicon glyphicon-th-list'></span> &nbsp; ".__d("sweet_forum", "Threads"),
                "/topics" => "<span class='glyphicon glyphicon-align-justify'></span> &nbsp; ".__d("sweet_forum", "Topics"),
            );
            $subitems = array(
                "/admin" => array(
                    "/admin" => __d("sweet_forum", "Dashboard"),
                    "/admin/settings/index" => __d("sweet_forum", "Settings"),
                ),
                "/users" => array(
                    "/admin/users/index" => __d("sweet_forum", "All users"),
                    "/admin/users/banned" => __d("sweet_forum", "Banned users"),
                    "/admin/users/reports" => __d("sweet_forum", "Users reports"),
                ),
                "/threads" => array(
                    "/admin/threads/index" => __d("sweet_forum", "All threads"),
                ),
                "/topics" => array(
                    "/admin/topics/index" => __d("sweet_forum", "All topics"),
                )
            );

            echo $this->element('parts/nav_output', array('items' => $items, 'subitems' => $subitems, 'current_nav' => $current_nav));
        ?>
    </ul>
</div>