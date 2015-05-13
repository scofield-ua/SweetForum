<div class='row'>
    <div class='col-md-12'>
        <div class="page-header">
            <h3><?= $page_title; ?></h3>
        </div>
    </div>
</div>

<div class='row'>
    <div class='col-md-12'>
        <?php
            if(empty($topics)) {
                echo $this->element('parts/nothing');
            } else {
                foreach($topics as $topic) {
                    echo "
                        <div class='row'>
                            <div class='col-md-12'>
                                <h5 class='margin-top0'><strong>".$this->Html->link($topic['Topic']['name'], SWEET_FORUM_BASE_URL.'topic/'.$topic['Topic']['url'])."</strong></h5>
                                <ul class='list-inline small text-muted'>
                                    <li>".date('d.m.Y H:i', strtotime($topic['Topic']['created']))."</li>
                                    <li>
                                        <div class='btn-group btn-group-xs'>
                                            ".$this->Html->link("<span class='glyphicon glyphicon-user'></span> ".$topic['Creator']['Info']['name'], SWEET_FORUM_BASE_URL.'u/'.$topic['Creator']['Info']['username'], array('class' => 'btn btn-default', 'escape' => false))."
                                            ".$this->Html->link("<span class='glyphicon glyphicon-pencil'></span> ".__d('sweet_forum', 'Edit'), SWEET_FORUM_BASE_URL.'topics/edit/'.$topic['Topic']['url'], array('class' => 'btn btn-default', 'escape' => false))."
                                        </div>
                                    </li>
                                </ul>
                                <hr>
                            </div>
                        </div>
                    ";
                }

                echo $this->element('parts/pagination');
            }
        ?>
    </div>
</div>