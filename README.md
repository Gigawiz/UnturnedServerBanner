# UnturnedServerBanner
Unturned Server Banner is an addon that allows you to have a website banner with live stats for your Unturned gameserver!

##Installing
###Installing the PHP System
1.Choose where you will put the files. I will assume for these instructions that they are housed in /unturned_banner/

2.Upload all files from the PHP directory, except for the included .sql file.

3.Open config.php and edit the first 9 lines to your liking

 *MySql is optional, however this tutorial assumes you are using it.
 
4.Import the included database to your MySql server

5.Fill in the MySql details in the config.php file

6.Save config.php

7.Install the Plugin

8.Put the url to 'update_server.php' in 'Unturned\Servers\{your server name}\Rocket\Plugins\UnturnedServerBanner\UnturnedServerBanner.configuration.xml'

9.Link anyone to http://yoursite.com/unturned_banner/genimg.php?ip={your server ip address}

10.Have some coffee and enjoy!
 
 
 
###Installing the Plugin

1.Install the plugin 'UnturnedServerBanner.dll' to your RocketMod based Unturned server.

2.Start the server, then shut it back down

3.Navigate to Unturned\Servers\{your server name}\Rocket\Plugins\UnturnedServerBanner

4.Open 'UnturnedServerBanner.configuration.xml' in a word processor

5.Enter the API key provided by the host site (if any)

 *If you are using this on your own web server, you do not need an API key, 

6.Change "http://www.yourbannersite.com" to the address of the host site (no trailing /)

 *If you are using this on your own web server, put the address of the PHP script (no trailing /)

7.Save

8.Launch unturned and visit the link provided by your host

 *If you are using your own hosting, the default file for your banner is /{your install directory}/genimg.php

  *You may change the name of this file if you wish