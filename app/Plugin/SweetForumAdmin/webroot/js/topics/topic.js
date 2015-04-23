$(document).ready(function() {
    $('#main-submit-button').click(function() {
        $(this).parents('form').find('input[name*=type]').val(0);
    });
    $('#preview-submit-button').click(function() {
        $(this).parents('form').find('input[name*=type]').val(1);
    });
    $('#delete-submit-button').click(function() {
        if(confirm("Вы уверены?")) {
            $(this).parents('form').find('input[name*=type]').val(2);
        } else {
            return false;
        }
    });
});