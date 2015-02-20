<?php
/*
 * MLReserver is a reservation system primarily made for making sharing items
 * easy and clear between a large group of people.
 * Copyright (C) 2015 soud
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

class MainController
{
    const DEFAULT_METHOD_NAME = 'index';

    private $controller;
    private $method;
    private $params;
    private $url_actions;

    private $routes = array(
        'user'    => 'UserController',
        'item'    => 'ItemController',
        'reserve' => 'ReserveController'
    );

    protected $title;
    protected $model;

    public function __construct()
    {
        $this->route();

        if (!file_exists(APP . "controllers/$this->controller.php"))
            $this->error('Page not found.', 404);

        $this->controller = new $this->controller();

        if (!$this->method)
            if (method_exists($this->controller, self::DEFAULT_METHOD_NAME))
                $this->method = self::DEFAULT_METHOD_NAME;

        if (method_exists($this->controller, $this->method)) {
            if (!$this->params)
                call_user_func(array($this->controller, $this->method));
            else
                call_user_func_array(array($this->controller, $this->method), $this->params);
        } else
            $this->error('Page not found.', 404);
    }

    /**
     * Set the controller, method and parameters by exploding the url
     *
     * @return void
     */
    private function route()
    {
        $this->url_actions = isset($_GET['a']) ? strtolower($_GET['a']) : null;

        if (!$this->url_actions)
            if (!isset($_SESSION['logged_in']))
                $this->url_actions = 'user/login';
            else
                $this->url_actions = 'item';

        $this->url_actions = explode('/', $this->url_actions);

        if (!array_key_exists($this->url_actions[0], $this->routes))
            $this->error('Page not found.', 404);

        $this->controller = $this->routes[$this->url_actions[0]];
        $this->method     = isset($this->url_actions[1]) ? $this->url_actions[1] : null;
        $this->params     = array_slice($this->url_actions, 2);
    }

    protected function view($route, $page)
    {
        require_once APP . 'views/layout/head.php';

        if (isset($_SESSION['logged_in'])) {
            require_once APP . 'views/layout/header.php';

            require_once APP . 'views/layout/nav.php';
        }

        require_once APP . "views/$route/$page.php";

        require_once APP . 'views/layout/footer.php';
    }

    protected function error($error_message, $status_code = 200)
    {
        http_response_code($status_code);
        $this->title = 'Reserver - Oops!';

        require_once APP . 'views/layout/head.php';

        require_once APP . 'views/error.php';

        require_once APP . 'views/layout/footer.php';

        exit(1);
    }
}
