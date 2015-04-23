<div class="modal fade" id="wtycu-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?= __d("sweet_forum", "What tags you can use"); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                    echo $this->element('locale-texts/what-tags-you-can-use-'.$sf_forum_lang);
                ?>
                <p><strong></strong></p>
            </div>            
        </div>
    </div>
</div>