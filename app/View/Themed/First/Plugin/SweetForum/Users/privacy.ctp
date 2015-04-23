<h1 class='page-h1'><?= $page_title; ?></h1>

<?php
echo $this->Form->create('Privacy', array('class' => 'simple checkboxes margin-top'));
echo $this->Form->input('show_social_link', array('checked' => (bool) $user['Privacy']['show_social_link'], 'label' => 'Показывать ссылку на социальную сеть', 'type' => 'checkbox'));
echo $this->Form->end('Сохранить настройки');
echo $this->Session->flash();
?>
