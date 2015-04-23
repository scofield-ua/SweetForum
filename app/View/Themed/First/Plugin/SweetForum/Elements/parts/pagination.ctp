<ul class="pager">    
    <?php
        $this->Paginator->options(array(
            'convertKeys' => array('page')
        ));
    
        if($this->Paginator->hasPrev()) {
            echo "<li class='previous'>".$this->Html->link('&larr; '.__d('sweet_forum', 'Newer'), '?page='.($current_page-1), array('escape' => false))."</li>";
        }
        if($this->Paginator->hasNext()) {
            echo "<li class='next'>".$this->Html->link(__d('sweet_forum', 'Older').' &rarr;', '?page='.($current_page+1), array('escape' => false))."</li>";
        }
    ?>
</ul>