var socket;
var deviceID = "123";
$(function() { 
		
  socket = io('http://'+document.domain+':2020');


  // Socket events
  socket.on('action', function (data) {
  		  cl(data);
			if(data.action=="clear") {
				//$('#desktop').html("---");
			}
				
  });
  
  socket.emit('action', {"action": "initrun"} );
  
	
});

function openURL(url) {
	socket.emit('action', {"action": "openurl", "url": url} );
}
function openApp(app) {
	socket.emit('action', {"action": "runapp", "app": app} );
}

function cl(X) {
	console.log(X);
}