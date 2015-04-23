$(document).ready(function() {
    var modal = $('#password-complete-modal');
    var toId;
    modal.modal('show');    
    
    modal.find('form').submit(function() {
        var t = $(this);
        var button = t.find('input[type=submit]');
        var url = t.prop('action');
        var data = t.serialize();
        var passwordField = t.find('input.password-field');
        var passwordFieldValue = $.trim(passwordField.val());
        var alert = modal.find('.alert');
        
        if (passwordFieldValue == '') {
            passwordField.focus();
            return false;
        }
        
        $.ajax({
            'url' : url,
            'type' : 'post',
            'data' : data,
            'beforeSend' : function() {
                clearTimeout(toId);
                alert.hide();
                alert.removeClass('alert-success').removeClass('alert-danger');
                button.button('loading');
            },
            'complete' : function() {
                t.find('input[type=submit]').button('reset');
            },
            'success' : function(data) {
                if (data.error) {
                    alert.text(data.message).addClass('alert-danger').removeClass('hide').fadeIn(250);
                } else {
                    alert.text(data.message).addClass('alert-success').removeClass('hide').fadeIn(250);
                    toId = setTimeout(function() {
                        modal.modal('hide');
                    }, 3000);
                }
            },
            'error' : function() {
                alert('Error');
            },
            'dataType' : 'JSON'
        });
        return false;
    });
});