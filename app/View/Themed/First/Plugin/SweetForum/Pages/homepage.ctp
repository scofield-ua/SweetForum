
<?php
if(count($threads) > 0) {
    echo "<ul class='homepage-threads'>";
    foreach($threads as $t) {
        $name = "<span class='name'>".$t['Thread']['name']."</span> <span class='desc'>".$t['Thread']['description']."</span>";

        echo "<li>".$this->Html->link($name, "/threads/view/".$t['Thread']['url'], array('escape' => false))."</li>";
    }
    echo "</ul>";
}

/*if(!empty($blog)) {
    echo "
        <div class='last-blog-post'>
            <p><strong>Новости форума:</strong> ".$this->Html->link($blog['Blog']['name'], '/blog/'.$blog['Blog']['url'])."</p>
        </div>
    ";
}*/
?>
