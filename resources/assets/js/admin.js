$( document ).ready(function() {
    hideAllCommandExtras();
    $('.admin select#command').change(function(){
        showCommandExtra();
    });
    showCommandExtra();
});

function hideAllCommandExtras() {
    $('.admin .command-extra').hide();
}

function showCommandExtra() {
    hideAllCommandExtras();
    var command = $('.admin select#command option:selected').val();
    $('.admin div#'+command).show();
}
