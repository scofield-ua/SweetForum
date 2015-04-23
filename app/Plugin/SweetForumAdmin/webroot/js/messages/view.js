$("html, body").animate({ scrollTop: $("#MessageViewForm").offset().top }, 100);

$(document).ready(function() {
    // add fixed header    
    $('body').append($('.page-header').clone().addClass('messages-fixed hide'));
    
    var iframesToLoad = $('a.iframe-to-load');
    if (iframesToLoad.length > 0) {
        iframesToLoad.click(function () {
            var t =  $(this);
            var url = sweetForum.baseUrl + t.data('url')+"?url="+t.prop("href")+"&type="+t.data('content-type');
            
            $.get(url, function(data) {
                if (data.html !== undefined) {
                    t.html(data.html);                    
                }
            }, "JSON");
            
            return false;
        });
    }
    
    $.getScript("/sweet_forum/First/js/venobox/venobox.min.js", function() {
        $('.venobox').venobox({
            'numeratio' : true,
        });
    });
    
    $('.edit-delete').click(deleteMessage);
    $('.edit-hide').click(hideMessage);
    $('.conv.block-user').click(blockUser);
});

$(window).scroll(function() {
    var scrolled = $(window).scrollTop();
    
    console.log(scrolled);
    
    if (scrolled > 150) {
        $('.page-header.messages-fixed').stop(1, 1).removeClass('hide');
    } else {
        $('.page-header.messages-fixed').stop(1, 1).addClass('hide');
    }
});

function deleteMessage() {
    var t = $(this);
    t.parents('.item').remove();
    $.post(t.prop('href'));
    
    return false;
}

function hideMessage() {
    $(this).parents('.item').remove();
    return false;
}

function blockUser() {
    var t = $(this);
    var toRemove = $('.page-header, .messages-output, #MessageViewForm');
    
    $.ajax({
        'url' : t.prop('href'),
        'type' : 'post',
        'beforeSend' : function() {
            toRemove.fadeTo(250, 0.25);
        },
        'success' : function(data) {
            if (data.success) {
                toRemove.hide();
                
                var modal = $('#small-message-modal');
                modal.find('.modal-body').html(data.message);
                modal.modal('show');
                
                window.location = sweetForum.baseUrl+'messages/index';
            }
        },
        'dataType' : 'JSON'
    });
    
    return false;
} 