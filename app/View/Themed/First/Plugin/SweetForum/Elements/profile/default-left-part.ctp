<div class='col-md-12 col-sm-6'>
    <?= $this->Html->image($user_data['Info']['avatar']."?s=100"); ?>
</div>
<div class='col-md-12 col-sm-6 margin-top15'>
    <ul class='profile-links text-center'>
        <li><?= $this->Html->link('<span class="badge pull-right" id="unread-conv-cont"></span>'.__d("sweet_forum", "Messages"), SWEET_FORUM_BASE_URL.'messages/index', array('escape' => false)); ?></li>
        <li><?= $this->Html->link(''.__d("sweet_forum", "Edit profile"), SWEET_FORUM_BASE_URL.'users/edit', array('escape' => false)); ?></li>
        <li><?= $this->Html->link(''.__d("sweet_forum", "Settings"), SWEET_FORUM_BASE_URL.'users/settings', array('escape' => false)); ?></li>
        <li><?= $this->Html->link(''.__d("sweet_forum", "Sign out"), SWEET_FORUM_BASE_URL.'users/signout', array('escape' => false)); ?></li>
    </ul>
</div>