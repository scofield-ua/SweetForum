<div class="btn-group-vertical" style="width:100%">
    <?php        
        $names = array(
            "index" => __d("sweet_forum", "Inbox"),
            "other" => __d("sweet_forum", "Other"),
            "archived" => __d("sweet_forum", "Archived"),
            "blocked" => __d('sweet_forum', 'Blocked')
        );
        foreach($names as $url => $name) {
            $class = $this->action == $url ? "active" : "";
            
            echo $this->Html->link($name, SWEET_FORUM_BASE_URL.'messages/'.$url, array('class' => 'btn btn-default '.$class, 'escape' => false));
        }
    ?>    
</div>
