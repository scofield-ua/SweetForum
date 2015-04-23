$(document).ready(function() {
    var defaultModal = $('#small-message-modal');
    
    $('a.ban').click(function() {
        $.post($(this).prop('href'), function(data) {
            defaultModal.find('.modal-body').text(data);
            defaultModal.modal('show');
            
            setTimeout(function() {
                defaultModal.modal('hide');
            }, 5000);
        });
        
        return false;
    });
});