$(document).ready(function() {
    $('nav a.signin').click(function() {    
        var modal = $($(this).data('target'));
        modal.modal('show');
        return false;
    });    
});