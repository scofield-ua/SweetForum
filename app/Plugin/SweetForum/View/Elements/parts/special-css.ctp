<?php
if(isset($special_css)) {
    if(is_array($special_css)) {
        foreach($special_css as $css) {
            echo $this->Html->css($css);
        }
    }
}