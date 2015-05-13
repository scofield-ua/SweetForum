<div class='row'>
    <div class='col-md-12'>
        <div class="page-header">
            <h3><?= __d('sweet_forum', 'Users'); ?></h3>
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <form class='form-inline'>
            <div class='form-group'>
                <input type='text' value='<?= isset($search_email) ? $search_email : ''; ?>' placeholder='<?= __d('sweet_forum', 'Search by email'); ?>' name='email' class='form-control input-sm' />
            </div>
            <div class='form-group'>
                <input type='submit' class='btn btn-primary btn-sm' value='<?= __d('sweet_forum', 'Search'); ?>' />
            </div>
            <div class='form-group'>
                <?= $this->Html->link(__d('sweet_forum', 'Cancel'), '/admin/users/index', array('class' => 'btn btn-default btn-sm')); ?>
            </div>
        </form>
    </div>
</div>

<div class='row margin-top15'>
    <div class='col-md-12'>
        <?php
            if(!empty($users)) {
                echo "<table class='table table-bordered'>";
                echo "
                    <tr>
                        <th class='col-md-1'>ID</th>
                        <th class='col-md-3'>".__d('sweet_forum', 'Email')."</th>
                        <th class='col-md-2'>".__d('sweet_forum', 'Name')."</th>
                        <th class='col-md-2'>".__d('sweet_forum', 'Created')."</th>
                        <th class='col-md-2'>IP</th>
                        <th class='col-md-1'></th>
                    </tr>
                ";

                foreach($users as $user) {
                    $ban_class = (int) $user['User']['status'] == -1 ? 'active' : '';
                    
                    $options = '
                        <div class="btn-group">
                            '.$this->Html->link('<span class="glyphicon glyphicon-off"></span>', SWEET_FORUM_BASE_URL.'admin/users/ban/'.$user['User']['id'], array('class' => 'btn btn-xs btn-default ban '.$ban_class, 'title' => __d('sweet_forum', 'Ban'), 'escape' => false)).'
                            <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-cog"></span>
                            </button>                            
                            
                            <ul class="dropdown-menu text-left pull-right" role="menu">                                
                                <li>'.$this->Html->link(__d("sweet_forum", "Edit"), SWEET_FORUM_BASE_URL.'admin/users/edit/'.$user['User']['id'], array('escape' => false)).'</li>                                
                                <li>'.$this->Html->link(__d("sweet_forum", "Group"), SWEET_FORUM_BASE_URL.'admin/users/group/'.$user['User']['id'], array('escape' => false)).'</li>                                
                                <li>'.$this->Html->link(__d("sweet_forum", "Delete"), SWEET_FORUM_BASE_URL.'admin/users/edit/'.$user['User']['id'], array('confirm' => __("Are you sure?"), 'escape' => false)).'</li>
                            </ul>
                        </div>
                    ';

                    echo "
                        <tr>
                            <td><small>".$user['User']['id']."</small></td>
                            <td><small>".$user['User']['email']."</small></td>
                            <td><small>".$user['Info']['name']."</small></td>
                            <td><small>".$user['User']['created']."</small></td>
                            <td><small>".$user['User']['ip']."</small></td>
                            <td class='text-center'><small>{$options}</small></td>
                        </tr>
                    ";
                }

                echo "</table>";
            } else {
                echo '<em>'.__d('sweet_forum', 'Nothing found').'</em>';
            }
        ?>
    </div>
</div>

<?php
echo $this->Html->script(array('SweetForumAdmin.users/index'));
?>