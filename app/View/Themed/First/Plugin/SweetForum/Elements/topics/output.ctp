<?php
if(empty($topics)) {
    echo $this->element('parts/nothing-found', array('text' => __d("sweet_forum", "No topics yet")));
} else {
    echo "<ul class='topics-list'>";
    foreach($topics as $topic) {
        $img = $this->Html->image($topic['Creator']['Info']['avatar'].'?s=20', array('title' => $topic['Creator']['Info']['name'], 'class' => 'pull-left'));
        $title = "<div class='title'>".$topic['Topic']['name']."</div>";
        $a = $this->Html->link($img.$title, SWEET_FORUM_BASE_URL.'topic/'.$topic['Topic']['url'], array('escape' => false));
        $date = $topic['Topic']['created'];
        $pretty = $this->PrettyTime->pretty($date);

        echo "
            <li>
                <h3>{$a}</h3>
                <time datetime='{$date}'>{$pretty}</time>
            </li>";
    }
    echo "</ul>";
}