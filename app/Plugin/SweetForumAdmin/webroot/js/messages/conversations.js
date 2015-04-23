$(document).ready(function() {
    $('span.remove').click(removeConversation);
    $('span.recovery').click(recoveryConversation);
    $('a.recovery-user').click(recoveryUser);    
});

function removeConversation() {
    var f = $(this).data('from');
    var li = $(this).parents('li.item');
    
    li.remove();
    
    $.post(sweetForum.baseUrl+'messages/remove_converstaion/'+f);
    
    return false;
}

function recoveryConversation() {
    var f = $(this).data('from');
    var li = $(this).parents('li.item');
    
    li.remove();
    
    $.post(sweetForum.baseUrl+'messages/recovery_converstaion/'+f);
    
    return false;
}

function recoveryUser() {
    var url = $(this).prop('href');
    var item = $(this).parents('.item');
    
    item.fadeOut(250, function() { item.remove(); });
    
    $.post(url);
    
    return false;
}