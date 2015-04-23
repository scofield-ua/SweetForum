<?php
if(isset($special_js)) {
    if(is_array($special_js)) {
        foreach($special_js as $js) {
            echo $this->Html->script($js);
        }
    }
}