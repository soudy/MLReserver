<?php

session_set_cookie_params(3600 * 24 * 183);
session_start();

define('APP', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);

function __autoload($class)
{
    if (file_exists(APP . "controllers/$class.php"))
        require_once APP . "controllers/$class.php";

    if (file_exists(APP . "models/$class.php"))
        require_once APP . "models/$class.php";
}

require_once APP . 'config/config.php';
require_once APP . 'config/database.php';

$controller = new MainController();
