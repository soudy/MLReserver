<?php

class MainController
{
    protected $controller;
    protected $method;
    protected $params;

    private $routes = array(
        'user' => 'UserController',
        'home' => 'HomeController',
        'item' => 'ItemController'
    );

    protected $title;
    protected $url_actions;

    public function __construct()
    {
        // TODO: find a better way to do this
        /*
         * if ($_COOKIE['uid'] && $_COOKIE['session']) {
         *     $user = new User();
         *     if ($user->check_user_session())
         *         $_SESSION['logged_in'] = $_COOKIE['uid'];
         * }
         */

        $this->route();

        if (DEBUG) {
            echo "logged_in: {$_SESSION['logged_in']}<br />";
            echo 'url: ' . URL . '<br />';
            echo "controller: $this->controller<br />";
            echo 'url_actions: ';
            var_dump($this->url_actions);
            echo "method: $this->method<br />";
            echo 'params: ';
            var_dump($this->params);
        }

        if (!file_exists(APP . "controllers/$this->controller.php"))
            $this->error('Page not found.');

        $this->controller = new $this->controller();

        if (!$this->method)
            if (method_exists($this->controller, 'index'))
                $this->method = 'index';

        if (method_exists($this->controller, $this->method)) {
            if (!$this->params)
                call_user_func(array($this->controller, $this->method));
            else
                call_user_func_array(array($this->controller, $this->method), $this->params);
        } else {
            $this->error('Page not found.');
        }
    }

    /**
     * Set the controller, method and parameters by exploding the url
     *
     * @return void
     */
    private function route()
    {
        $this->url_actions = strtolower($_GET['a']);

        if (!$this->url_actions)
            if (!$_SESSION['logged_in'])
                $this->url_actions = 'user/login';
            else
                $this->url_actions = 'item';

        $this->url_actions = split('/', $this->url_actions);

        $this->controller = $this->routes[$this->url_actions[0]];
        $this->method     = $this->url_actions[1];
        $this->params     = array_slice($this->url_actions, 2);
    }

    protected function view($route, $page)
    {
        require_once APP . 'views/layout/head.php';

        if ($_SESSION['logged_in']) {
            require_once APP . "views/layout/header.php";

            require_once APP . "views/layout/nav.php";
        }

        require_once APP . "views/$route/$page.php";

        require_once APP . 'views/layout/footer.php';
    }

    protected function error($error_message)
    {
        $this->title = 'Reserver - Oops!';

        require_once APP . 'views/layout/head.php';

        if ($_SESSION['logged_in']) {
            require_once APP . "views/layout/header.php";

            require_once APP . "views/layout/nav.php";
        }

        require_once APP . "views/error.php";

        require_once APP . 'views/layout/footer.php';

        exit(1);
    }
}
