$(document).ready(function() {
    $('.send-message-to-user').click(defaultShowModal);
    $('.report-user').click(defaultShowModal);
    $('#MessageViewForm').submit(defaultSendForm);
    $('#UserReportViewForm').submit(defaultSendForm);
});

function defaultShowModal() {
    var t = $(this);
    var modal = $(t.data('target'));
    
    modal.modal('show');
    
    return false;
}

function defaultSendForm() {
    var form = $(this);
    var button = form.find('input[type=submit]');    
    var alertCont = form.parents('.modal-body').find('.alert');
    
    $.ajax({
        'url' : form.prop('action'),
        'type' : 'post',
        'data' : form.serialize(),
        'beforeSend' : function() {
            alertCont.addClass('hide').removeClass('alert-success').removeClass('alert-danger');            
            button.button('loading');
        },
        'complete' : function() {
            button.button('reset');
        },
        'success' : function(data) {
            try {
                if (data.success) {
                    form.hide();
                    alertCont.addClass('alert-success');
                } else {
                    alertCont.addClass('alert-danger');
                }
                alertCont.html(data.message).removeClass('hide');                
            } catch(e) {}
        },
        'error' : function() {
            alert('Error');
        },
        'dataType' : 'JSON'
    });
    
    return false;
}