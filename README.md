# KIDesktop
A pi-driven Kids-Desktop for safe internet- and application-usage

![example installation](https://kidesktop.mad5.de/resources/images/kidesktop.jpg)

# install

first get your [raspberry pi](https://www.raspberrypi.org/) running and install the latest raspian: [from here](https://www.raspberrypi.org/downloads/raspbian/)

__prerequisites__

##Adminstrative account 

create an account at https://kidesktop.mad5.de  
go to your settings and write down your mainkey.  
insert at least one system and write down the systemkey.  


##install your pi

	sudo apt-get update
	sudo apt-get install wmctrl xdotool php5 chromium-browser

if your desktop appears and you set all your localization and other options right install Kidesktop

	cd ~
	git clone https://github.com/mad5/Kidesktop.git
	cd Kidesktop

rename config.json.dist to config.json and edit.  
set mainkey- and key-values which you wrote down before.  
Add allowed hosts. The host where your __Kidesktop-adminstation__ is installed must set in here.  
	
# run for testing

	./run test #not set to background and titlebar not hidden
	
or
	
	./run # as desktop
	
# run at boottime

edit ~/.config/lxsession/LXDE.pi/autostart and add at the end

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

__html5clock__  
ineedmoretime.org - A HTML5 analog clock experiment
[https://github.com/jakobwesthoff/ineedmoretime](https://github.com/jakobwesthoff/ineedmoretime)
