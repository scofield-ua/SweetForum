<h1 class='padding-bottom15'><?= $page_title; ?></h1>

<?php
if(isset($thread)) {
    echo "<p><strong>Поиск по ветке:</strong> <em>".$this->Html->link($thread['Thread']['name'], '/threads/view/'.$thread['Thread']['url'])."</em></p>";
}
?>

<?= $this->element('topics/output', array('topics' => $topics)); ?>