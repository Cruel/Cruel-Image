# Cruel Image

Cruel Image is an image hosting software solution designed for the LAMP stack. Additionally, Nginx configuration is included and many other database backends are planned for support soon.

## Installation

1. Make your hosting directory and give it proper ownership and permissions.
2. For Apache, make sure AllowOverride is properly set to allow the .htaccess (there is only one in root dir).
3. For Nginx, adapt the [included configuration](https://github.com/Cruel/Cruel-Image/blob/master/nginx.conf) to your needs.
4. `git clone --recursive git://github.com/Cruel/Cruel-Image.git /your/directory/here`
5. Create a MySQL database for exclusive Cruel Image use.

6. #### Easy (Recommended)
Simply navigate to `http://yoursite.com/` (or `http://yoursite.com/directory/` if you put it there) to begin installation script.
#### Advanaced
Manually modify [inc/config.dist.php](https://github.com/Cruel/Cruel-Image/blob/master/inc/config.dist.php) and save it as `inc/config.php`

7. Delete the `/install` subdirectory.

## Configuration

TBA