var isSending = false;
$(document).ready(function() {
    $('a.complaint').click(makeMark);
    $('.comments a.reply').click(replyAction);
    $('.add-comment .reply-cancel').click(cancelReplyAction);
    $('select.change-comments-sorting').change(changeCommentsSorting);    
    
    var iframesToLoad = $('a.iframe-to-load');
    if (iframesToLoad.length > 0) {
        iframesToLoad.each(function(i, e) {            
            var url = sweetForum.baseUrl + $(e).data('url')+"?url="+$(e).prop("href")+"&type="+$(e).data('content-type');
            
            $.get(url, function(data) {
                if (data.html !== undefined) $(e).html(data.html);            
            }, "JSON");
        });
    }    
    
    $.getScript("/sweet_forum/First/js/venobox/venobox.min.js", function() {
        $('.venobox').venobox({
            'numeratio' : true,
        });
    });
});

function makeMark() {
    var t = $(this);
    var modal = $('#small-message-modal');
    
    var li = "";    
    if (t.parents('.answer-item').length > 0) li = t.parents('.answer-item')
    else if (t.parents('.item').length > 0) li = t.parents('.item');

    if(!isSending) {
        $.ajax({
            'url' : t.prop('href'),
            'type' : 'post',
            'beforeSend' : function() {
                if (li != "") {
                    li.fadeTo(250, 0.25);
                    isSending = true;
                }
                
                t.fadeTo(250, 0.25);
            },
            'complete' : function() {
                if (li != "") {
                    li.fadeTo(250, 1);
                    isSending = false;
                }
                
                t.fadeTo(250, 1);
            },
            'success' : function(data) {
                if(data !== null) {
                    if(data.message !== undefined) {
                        modal.find('.modal-body').html(data.message);
                        modal.modal('show');                        
                    }
                    
                    if(data.success !== undefined) {
                        if (data.success && li != "") li.remove();
                    }
                }
            },
            'dataType' : 'JSON'
        });
    }

    return false;
}

function cancelReplyAction() {
    var t = $(this);
    var forReply = $('.add-comment .for-reply');
    var replyCancelButton = $('.add-comment .reply-cancel');
    var input = $('.add-comment').find('input[name*=answer_to][type=hidden]');
        
    forReply.addClass('hide');
    forReply.find('.panel-body').html("");
    
    replyCancelButton.addClass('hide');
    
    input.val("");
    
    return false;
}

function replyAction() {
    var t = $(this);
    var forReply = $('.add-comment .for-reply');
    var replyCancelButton = $('.add-comment .reply-cancel');
    var commentItem = t.parents('li.item');
    var commentHtml = commentItem.find('.comment-text').html();
    var input = $('.add-comment').find('input[name*=answer_to][type=hidden]');
    
    forReply.find('.panel-body').html(commentHtml);
    forReply.removeClass('hide');
    
    replyCancelButton.removeClass('hide');
    
    input.val(t.data('to'));
    
    $('html, body').animate({
        scrollTop: replyCancelButton.offset().top
    }, 500);
    
    return false;
}

function lockForm() {
    var t = $(this);
    var commentItem = t.parents('li.item');
    var formDiv = $('.add-comment');
    var top = t.offset().top; 
    var formTop = top - commentItem.offset().top + 28;
    var formLeft = 30;
    var formWidth = commentItem.width() * 1;    
    var form = formDiv.find('form');
    var input = form.find('input[name*=answer_to][type=hidden]');
    
    if (!t.data("clicked")) {
        // set other buttons to default
        $('.comments a.reply').text(t.data('reply-text'));
        $('.comments a.reply').data("clicked", false);
        $('.comments ol.answers .buttons').removeClass('show').addClass('invisible');
        
        t.text(t.data('close-text'));
        
        formDiv.css('top', formTop).css('left', formLeft).css('width', formWidth).addClass('lock');
        if(!formDiv.hasClass('lock')) formDiv.addClass('lock');
        formDiv.find('h4').hide();        
        
        input.val($(this).data('to'));
        
        $("body").animate({ scrollTop: top - 25}, 200);
        
        t.parents('.buttons').addClass('show').removeClass('invisible');
        
        t.data("clicked", true);
    } else {
        t.text(t.data('reply-text'));
        formDiv.removeClass('lock').removeAttr('style');
        formDiv.find('h4').show();
        input.val('');
        t.data("clicked", false);
        
        t.parents('.buttons').removeClass('show');
    }

    return false;
}

function changeCommentsSorting() {    
    window.location = updateQueryStringParameter(window.location.href, 'comments', $(this).val()) + "#comments";
}