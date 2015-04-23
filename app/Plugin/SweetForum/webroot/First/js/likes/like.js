var isSending = false;
$(document).ready(function() {
    $('button.like').click(function() {
        if(!isSending) {
            var t = $(this);
            var url = $.trim(t.data('url'));            
            
            if (url != "") {
                $.ajax({
                    'url' : url,
                    'data' : {'type' : t.data('type'), 'for' : t.data('for')},
                    'type' : 'post',
                    'beforeSend' : function() {
                        t.addClass('btn-primary').removeClass('btn-default');                        
                        isSending = true;
                    },
                    'complete' : function() {                        
                        isSending = false;
                    },
                    'success' : function(data) {
                        if(data !== null) {
                            if(data.success !== undefined) {
                                var counter = t.find('.count').text() * 1;
                                
                                switch (data.type) {
                                    case 'add' :
                                        counter++;
                                        t.find('.count').text(counter);
                                    break;
                                    case 'delete' :
                                        counter--;
                                        if (counter == 0) counter = "";
                                        t.removeClass('btn-primary').addClass('btn-default');                                        
                                        t.find('.count').text(counter);
                                    break;
                                }                                
                            }
                        }                        
                    },
                    'error' : function() {
                        t.removeClass('btn-primary').addClass('btn-default');
                    },
                    'dataType' : 'JSON'
                });
            }
        }
    });
});