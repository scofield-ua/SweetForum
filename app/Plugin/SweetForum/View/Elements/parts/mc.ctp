<?php
$special_var = "special_min_{$type}";
if(isset($$special_var)) if(is_array($$special_var)) $files = array_merge($files, $$special_var);

if(!empty($files)) {
    $url = SWEET_FORUM_BASE_URL."mc/".$type."?";
    
    foreach($files as $f) $url .= "files[]={$f}&";    
    $url = substr($url, 0, -1);
    
    $cache = isset($cache) ? $cache : false;    
    
    $url .= "&cache={$cache}";
    if(isset($root)) $url .= "&root={$root}";
    
    switch($type) {
        case 'css' : echo $this->Html->css($url); break;
        case 'js' : echo $this->Html->script($url); break;
    }
}