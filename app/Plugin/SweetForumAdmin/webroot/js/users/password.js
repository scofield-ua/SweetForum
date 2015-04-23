$(document).ready(function() {
    var input = $('.password-field');
    var icon = input.siblings('.input-helper.eye');
    icon.click(function() {
        var hasClass = $(this).hasClass('glyphicon-eye-open');

        if (hasClass) {
            input.attr("type", "text");
            $(this).removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
        } else {
            input.attr("type", "password");
            $(this).removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
        }
    });
});