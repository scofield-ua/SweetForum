$(document).ready(function() {
    $.get(sweetForum.baseUrl+'messages/get_unread', function(data) {
        data *= 1;
        if (data > 0) {
            $('#unread-conv-cont').text(data);
        }
    });
});