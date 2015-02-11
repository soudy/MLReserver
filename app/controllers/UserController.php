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

class UserController extends MainController
{
    public function __construct()
    {
        $this->model = new User();

        if ($_SESSION['logged_in'])
            $this->permissions = $this->model->get_user_permissions($_SESSION['logged_in']);
    }

    public function index()
    {
        if ($_SESSION['logged_in'])
            $this->settings();
        else
            $this->login();
    }

    public function login()
    {
        if ($_SESSION['logged_in'])
            $this->error('You\'re already signed in.');

        $this->title = 'Reserver - Log in';
        $this->view('user', 'login');

        if (isset($_POST['login'])) {
            $username       = $_POST['username'];
            $password       = $_POST['password'];
            $stay_logged_in = $_POST['stay_logged_in'];

            // TODO: better error message
            try {
                $this->model->log_in($username, $password, $stay_logged_in);
                header('Location: ' . URL . 'item');
            } catch (Exception $e) {
                echo 'Log in failed: '. $e->getMessage();
            }
        }
    }

    public function settings()
    {
        if (!$_SESSION['logged_in'])
            $this->error('You need to be signed in to come here.');

        $this->user = $this->model->get_user($_SESSION['logged_in']);

        $this->title = 'Reserver - Settings';
        $this->view('user', 'settings');

        // TODO: input verifying
        if (isset($_POST['edit_user'])) {
            $email     = $_POST['email'];
            $full_name = $_POST['full_name'];

            // TODO: check if the two new given passwords match
            $password  = $_POST['confirm_new_password'];

            if (!(isset($email) || isset($full_name) || isset($password))) {
                echo 'Please fill in a field to change that property.';
                return false;
            }

            if ($this->model->edit_user($_SESSION['logged_in'], null,
                                        $email, $full_name, null, $password))
                header('Location: ' . URL . 'user/all');

        }
    }

    public function logout()
    {
        if (!$_SESSION['logged_in']) {
            $this->login();
            return false;
        }

        $this->model->logout();
    }

    public function add()
    {
        if (!$_SESSION['logged_in'])
            $this->error('You need to be signed in to come here.');

        if (!$this->permissions->can_change_users) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - Add user';
        $this->view('user', 'add');

        // TODO: check for valid email and full name
        if (isset($_POST['add_user'])) {
            $full_name    = $_POST['full_name'];
            $email        = $_POST['email'];
            $access_group = $_POST['access_group'];

            try {
                $this->model->add_user($full_name, $email, $access_group);
                header('Location: ' . URL . 'user/all');
            } catch (Exception $e) {
                echo 'Adding user failed: ' . $e->getMessage();
            }
        }
    }

    public function all($uid)
    {
        if (!$this->permissions->can_change_users) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - Edit users';
        $this->view('user', 'all');

    }

    public function edit($uid)
    {
        if (!($id || $this->permissions->can_change_users)) {
            $this->index();
            return false;
        }

        $this->user = $this->model->get_user($uid);

        $this->title = 'Reserver - Edit user';
        $this->view('user', 'edit');

        // TODO: input verifying
        if (isset($_POST['edit_user'])) {
            $username     = $_POST['username'];
            $email        = $_POST['email'];
            $full_name    = $_POST['full_name'];
            $access_group = $_POST['access_group'];

            try {
                $this->model->edit_user($uid, $username, $email, $full_name, $access_group);
                header('Location: ' . URL . "user/all#$uid");
            } catch (Exception $e) {
                echo 'Editing user failed: ' . $e->getMessage();
            }
        }
    }

    public function remove($uid)
    {

        if (!$this->permissions->can_change_users) {
            $this->index();
            return false;
        }

        if (!$uid) {
            $this->all();
            return false;
        }

        if (!$this->user = $this->model->get_user($uid))
            $this->error('User not found.');

        $this->title = 'Reserver - Remove user';
        $this->view('user', 'remove');

        if (isset($_POST['remove_user'])) {
            $this->model->remove_user($uid);
            header('Location:' . URL . 'user/all');
        }
    }
}
