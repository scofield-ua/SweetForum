<nav class="navbar navbar-default header" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#header-collapse-links">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?= $this->Html->link("SweetForum", SWEET_FORUM_BASE_URL, array("class" => "navbar-brand")); ?>
        </div>
        <div class="collapse navbar-collapse" id="header-collapse-links">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <?php
                        if($logged_in) {
                            $photo = $this->Html->image($user_data['Info']['avatar']);
                            echo $this->Html->link($photo.$user_data['Info']['name'], SWEET_FORUM_BASE_URL.'users/profile', array('escape' => false, 'class' => 'profile'));
                        } else {
                            echo $this->Html->link("<strong>+".__d("sweet_forum", "Sign In")."</strong>", SWEET_FORUM_BASE_URL.'users/signin?back='.$current_url, array('escape' => false, 'data-toggle' => 'model', 'data-target' => '#signin-modal', 'class' => 'signin'));
                        }
                    ?>
                </li>
            </ul>
            <?php
                echo $this->Form->create('Search', array('type' => 'get', 'url' => SWEET_FORUM_BASE_URL.'search', 'class' => 'navbar-form navbar-right', 'role' => 'search'));
                echo $this->Form->input('s', array('label' => false, 'div' => 'form-group', 'placeholder' => __d("sweet_forum", "Search"), 'class' => 'form-control', 'value' => isset($s) ? $s : ""));
                //echo $this->Form->hidden('thread', array('value' => $thread_info['Thread']['url']));
                echo $this->Form->end();            
            ?>        
        </div>
    </div>
</nav>