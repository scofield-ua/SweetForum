<?php
$dfiles = array('sweet_forum/First/js/jq.js', 'sweet_forum/First/js/bootstrap.min.js', 'sweet_forum/First/js/autoload.js'); // default js files to load

// add sing.js files if user not logged in yet
if(!$logged_in && !isset($hide_sign_modals)) $dfiles[] = 'sweet_forum/First/js/users/sign.js';

echo $this->element('parts/mc',
    array(
        'type' => 'js',
        'cache' => true,
        'files' => $dfiles
    )
);

# some special not minified js files
echo $this->element('parts/special-js');

if(!$logged_in && !isset($hide_sign_modals)) {
    echo $this->element('modals/signin');    
}
?>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="small-message-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class='modal-body text-center'></div>
        </div>
    </div>
</div>