<h1 class='page-h1 center'><?= $page_title; ?></h1>

<?php
echo $this->Form->create('Activity', array('class' => 'center styled-2'));
echo $this->Form->label('message', 'Текст жалобы', array('class' => 'block'));
echo $this->Form->input('message', array('label' => false, 'placeholder' => 'Опишите, по какой причине вы хотите пожаловаться на пользователя', 'type' => 'textarea', 'error' => false));
echo $this->Form->end('Отправить');
echo "<center>".$this->Session->flash()."</center>";
?>
