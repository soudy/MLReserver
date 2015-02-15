MLReserver, A Simple Reservation System
===================

MLReserver is a reservation system primarily made for making sharing items easy and clear between a large group of people. It comes with a request system that allows lesser privileged people to reserve items too, with a teacher or admin's permission.

##Installation:
**Requirements**:
PHP 5.4 or newer, OpenSSL, PDO, MySQL 15 or newer, Apache 2.4 or newer, php-mcrypt

Clone MLReserver in your server directory
>$ git clone https://github.com/kendaru/MLReserver

 Create MySQL database (default database name: reserver)
 >mysql> CREATE DATABASE reserver;

 Create MySQL user with full access to created database (default username: reserver)
 >mysql> GRANT ALL PRIVILEGES ON databasename.* TO 'username'@'localhost' IDENTIFIED BY 'password';

Go to the MLReserver directory and import reserver.sql
>$ mysql -u reserver -p reserver < reserver.sql

Change the constants in `MLReserver/app/config/database.php` to yours if needed.

##Logging in:
**soud**:  admin (admin)
**ppom**: ppom (teacher)

Users can be easily added and removed with an admin account.


