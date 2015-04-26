<?php
if(count($threads) > 0) {
    echo "<div class='row'>";
    echo "<div class='col-md-12'>";
    
    echo "<ul class='homepage-threads'>";
    foreach($threads as $t) {
        $name = "<span class='name'>".$t['Thread']['name']."</span> <span class='desc'>".$t['Thread']['description']."</span>";

        echo "<li>".$this->Html->link($name, SWEET_FORUM_BASE_URL."threads/view/".$t['Thread']['url'], array('escape' => false))."</li>";
    }
    echo "</ul>";
    
    echo "</div>";
    echo "</div>";
}
