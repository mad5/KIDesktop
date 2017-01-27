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
			if(data.action=="dbg") {
				$('#dbg').prepend("<div style='border-bottom:solid 1px gray;padding-bottom:5px;margin-bottom:5px;'>"+data.info+"</div>");
			}
				
  });
  var href = window.location.href+"";
  var cmd = href.substr(href.indexOf("?")+1); 
  
  socket.emit('action', {"action": "initrun", "cmd": cmd} );
  
  if(cmd=="test") $('#dbg').show();
	
});

function openURL(url) {
	socket.emit('action', {"action": "openurl", "url": url} );
}
function openCommand(app) {
	socket.emit('action', {"action": "runcommand", "app": app} );
}
function openApp(app) {
	socket.emit('action', {"action": "runapp", "app": app} );
}

function cl(X) {
	console.log(X);
}