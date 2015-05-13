<div class='row'>
    <div class='col-md-12'>
        <div class="page-header">
            <h3><?= __d('sweet_forum', 'Users reports'); ?></h3>
        </div>
    </div>
</div>

<div class='row margin-top15'>
    <div class='col-md-12'>
        <?php
            if(!empty($reports)) {
                echo "<table class='table table-bordered'>";
                echo "
                    <tr>
                        <th class='col-md-1'>ID</th>
                        <th class='col-md-2'>".__d('sweet_forum', 'User reported')."</th>
                        <th class='col-md-2'>".__d('sweet_forum', 'Reported by')."</th>
                        <th class='col-md-3'>".__d('sweet_forum', 'Message')."</th>
                        <th class='col-md-2'>".__d('sweet_forum', 'Date')."</th>                        
                    </tr>
                ";

                foreach($reports as $item) {
                    $is_today = $this->Time->isToday($item['UserReport']['created']);
                    $class = $is_today ? "warning" : "";                    
                    
                    echo "
                        <tr class='{$class}'>
                            <td><small>".$item['UserReport']['id']."</small></td>
                            <td>
                                <small>
                                    ".$this->Html->link($item['User']['Info']['name'], SWEET_FORUM_BASE_URL.'u/'.$item['User']['Info']['username'])."
                                    ".$this->Html->link('<span class="glyphicon glyphicon-user"></span>', '/admin/users/index?email='.$item['User']['email'], array('class' => 'btn btn-default btn-xs', 'escape' => false))."
                                </small>
                            </td>
                            <td>
                                <small>
                                    ".$this->Html->link($item['ByUser']['Info']['name'], SWEET_FORUM_BASE_URL.'u/'.$item['ByUser']['Info']['username'])."
                                    ".$this->Html->link('<span class="glyphicon glyphicon-user"></span>', SWEET_FORUM_BASE_URL.'admin/users/index?email='.$item['ByUser']['email'], array('class' => 'btn btn-default btn-xs', 'escape' => false))."
                                </small>
                            </td>
                            <td><small>".$item['UserReport']['report_message']."</small></td>
                            <td><small>".$item['UserReport']['created']."</small></td>                            
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