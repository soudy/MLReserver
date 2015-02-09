<?php

error_reporting(E_ALL);

define('BASE_URL', "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) .
                   DIRECTORY_SEPARATOR);
/* Remove the public part from the url */
define('URL', str_replace('public/', '', BASE_URL));
define('DEBUG', 0);
