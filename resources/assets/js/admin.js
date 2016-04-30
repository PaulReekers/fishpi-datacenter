var lastKey = false;
var lastTime = false;
var json = [];

$( document ).ready(function() {
    hideAllCommandExtras();
    $('.admin select#command').change(function(){
        showCommandExtra();
    });
    showCommandExtra();
    keyLogger();
});

function hideAllCommandExtras() {
    $('.admin .command-extra').hide();
}

function showCommandExtra() {
    hideAllCommandExtras();
    var command = $('.admin select#command option:selected').val();
    $('.admin div#'+command).show();
    json = [];
    lastKey = false;
    lastTime = false;
}

function keyLogger()
{
	$(document).keydown(function(event){
		if (!lastTime) {
			lastTime = (new Date()).getTime();
			switch(event.which){
				case 49: //1
				case 50: //2
				case 51: //3
				case 78: //n
					lastKey = event.which;
				break;
				default:
					lastKey = false;
			}		
		}
	});

	$(document).keyup(function(event){
		if (lastKey == event.which) {
 			var delta = (new Date()).getTime() - lastTime;
			switch(event.which){
				case 49: //1
					led = "green";
				break;
				case 50: //2
					led = "orange";
				break;
				case 51: //3
					led = "red";
				break;
				case 78: //n
					led = "clear";
				break;
			}
			json.push({'led':led,'time':delta/1000});
			$('.admin div#compose textarea#json').text(JSON.stringify(json));
		}
		lastTime = false;
	});
}