
Example Apache Config
=====================

<VirtualHost *:80>
	ServerName geoloqi.dev
	
	DocumentRoot "/var/www/geoloqi.com"
	<Directory "/var/www/geoloqi.com">
		Options +FollowSymLinks
		AllowOverride all
		allow from all
	</Directory>
</VirtualHost>

License
=======

Copyright 2010 by Geoloqi.com and contributors

See LICENSE
