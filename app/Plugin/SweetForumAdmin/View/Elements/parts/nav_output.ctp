<?php
foreach($items as $url => $name) {
    echo "<li class='nav-header'>{$name}</li>";

    if(array_key_exists($url, $subitems)) {
        foreach($subitems[$url] as $u => $n) {
            $li_class = $u == $current_nav ? "active" : "";

			echo "<li class='{$li_class}'>".$this->Html->link($n, $u, array('escape' => false))."</li>";
        }
    }
}
