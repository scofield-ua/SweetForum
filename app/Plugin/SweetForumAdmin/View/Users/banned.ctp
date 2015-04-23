<div class='row'>
    <div class='col-md-12'>
        <div class="page-header">
            <h3><?= __d('sweet_forum', 'Banned users'); ?></h3>
        </div>
    </div>
</div>

<div class='row margin-top15'>
    <div class='col-md-12'>
        <?php
            if(!empty($users)) {
                echo "<table class='table table-bordered'>";
                echo "
                    <tr>                        
                        <th class='col-md-4'>Email</th>
                        <th class='col-md-5'>".__d('sweet_forum', 'Name')."</th>                        
                        <th class='col-md-2'></th>
                    </tr>
                ";

                foreach($users as $user) {
                    $ban_class = (int) $user['User']['status'] == -1 ? 'active' : '';
                    
                    $options = '
                        <div class="btn-group">
                            '.$this->Html->link('<span class="glyphicon glyphicon-off"></span>', '/admin/users/ban/'.$user['User']['id'], array('class' => 'btn btn-xs btn-default ban '.$ban_class, 'title' => __d('sweet_forum', 'Unban'), 'escape' => false)).'
                        </div>
                    ';

                    echo "
                        <tr>
                            <td><small>".$user['User']['email']."</small></td>
                            <td><small>".$user['Info']['name']."</small></td>                            
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