# Kidesktop
A pi-driven Kids-Desktop for safe internet- and application-usage


# install

first get your raspberry pi running and install the latest raspian: [from here](https://www.raspberrypi.org/downloads/raspbian/)

if your desktop appears and you set all your localization and other options right install Kidesktop

	sudo apt-get install wmctrl xdotool php5 chromium-browser cifs-utils
	git clone https://github.com/mad5/Kidesktop.git
	cd Kidesktop
	
# run for testing

	./run
	
or
	
	./run test	#not set to background and titlebar not hidden
	
# run at boottime

rename config.json.dist to config.json and edit.  
Set hostname to your __Kideskadm__-installation.  
Set your devicekey. This is generated within __Kideskadm__.  
Add allowed hosts. The host where your __Kideskadm__ is installed must set in here.  

edit ~/.config/,xsession/LXDE.pi/autostart and add at the end

	@/home/pi/Kidesktop/run full

# other libraries and sources used

__Ventus WM__  
A window manager written in Javascript, HTML5 and CSS3.
[https://github.com/rlamana/Ventus](https://github.com/rlamana/Ventus)

__phpsocket.io__  
A server side alternative implementation of socket.io in PHP based on Workerman  
[https://github.com/walkor/phpsocket.io](https://github.com/walkor/phpsocket.io)

__workerman__  
An asynchronous event driven PHP framework for easily building fast, scalable network applications.  
[https://github.com/walkor/Workerman](https://github.com/walkor/Workerman)

__jquery__  
jQuery is a fast, small, and feature-rich JavaScript library. It makes things like HTML document traversal and manipulation, event handling, animation, and Ajax much simpler with an easy-to-use API that works across a multitude of browsers.  
[https://jquery.com/](https://jquery.com/)