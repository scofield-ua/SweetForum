<?php
if(!isset($light_header)) {
    echo $this->element('parts/header-default');
} else {
    echo $this->element('parts/header-light');
}
?>
