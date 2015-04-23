$(document).ready(function() {
    $('button#back-to-edit-button').click(goBack);
});

function goBack() {
    window.history.back();
}