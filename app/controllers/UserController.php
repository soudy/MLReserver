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
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index()
    {
        if (isset($_SESSION['logged_in']))
            $this->settings();
        else
            $this->login();
    }

    public function login()
    {
        if (isset($_SESSION['logged_in']))
            $this->error('You\'re already signed in.');

        $this->title = 'Reserver - Log in';

        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            try {
                $this->model->log_in($username, $password);
                header('Location: ' . URL . 'item');
            } catch (Exception $e) {
                $this->error_message = 'Log in failed: ' . $e->getMessage();
            }
        }

        $this->view('user', 'login');
    }

    public function settings()
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        $this->user  = $this->model->get_user($_SESSION['logged_in']);
        $this->title = 'Reserver - Settings';

        if (isset($_POST['change_settings'])) {
            $email                = $_POST['email'];
            $full_name            = $_POST['full_name'];
            $current_password     = $_POST['current_password'];
            $new_password         = $_POST['new_password'];
            $confirm_new_password = $_POST['confirm_new_password'];
            $send_reminders       = isset($_POST['send_reminders']) ? 1 : 0;
            $password             = $this->user->password;

            if (!empty($current_password) || !empty($new_password) || !empty($confirm_new_password)) {
                try {
                    $password = $this->model->new_password($this->user->id,
                                                           $current_password, $new_password,
                                                           $confirm_new_password);
                } catch (Exception $e) {
                    $this->error_message = 'Failed to change password: ' . $e->getMessage();
                    $this->view('user', 'settings');
                    return false;
                }
            }

            try {
                $this->model->edit_settings($this->user->id, $email,
                                            $full_name, $password, $send_reminders);
                $this->success_message = 'Settings successfully changed.';
                /* header('Location: ' . URL . 'user/settings'); */
            } catch (Exception $e) {
                $this->error_message = 'Failed to change settings: ' . $e->getMessage();
            }
        }

        $this->view('user', 'settings');
    }

    public function logout()
    {
        if (!isset($_SESSION['logged_in'])) {
            $this->login();
            return false;
        }

        $this->model->log_out();
        header('Location: ' . URL);
    }

    public function add()
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        if (!$this->model->get_permission('can_change_users')) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - Add user';

        if (isset($_POST['add_user'])) {
            $full_name    = $_POST['full_name'];
            $email        = $_POST['email'];
            $access_group = $_POST['access_group'];

            try {
                $this->model->add_user($full_name, $email, $access_group);
                // XXX: temporary disable redirecting until mailing works
                /* header('Location: ' . URL . 'user/all'); */
            } catch (Exception $e) {
                $this->error_message = 'Adding user failed: ' . $e->getMessage();
            }
        }

        $this->view('user', 'add');
    }

    public function all($order = 'ausername')
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        if (!$this->model->get_permission('can_change_users')) {
            $this->index();
            return false;
        }

        $this->order = $order;
        $this->users = $this->model->get_all_users($order);

        $this->title = 'Reserver - Edit users';
        $this->view('user', 'all');
    }

    public function edit($uid = null)
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        if (!$this->model->get_user($uid) || !$this->model->get_permission('can_change_users')) {
            $this->index();
            return false;
        }

        $this->user  = $this->model->get_user($uid);
        $this->title = 'Reserver - Edit user';

        if (isset($_POST['edit_user'])) {
            $username     = $_POST['username'];
            $email        = $_POST['email'];
            $full_name    = $_POST['full_name'];
            $access_group = $_POST['access_group'];

            try {
                $this->model->edit_user($uid, $username, $email, $full_name, $access_group);
                $this->success_message = 'User ' . $this->model->get_user($uid)->username .
                                         ' succesfully changed.';
                $this->all();
                exit(1);
            } catch (Exception $e) {
                $this->error_message = 'Editing user failed: ' . $e->getMessage();
            }
        }

        $this->view('user', 'edit');
    }

    public function remove($uid = null)
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        if (!$uid) {
            $this->remove_account();
            return false;
        }

        if (!$this->model->get_permission('can_change_users')) {
            $this->remove_account();
            return false;
        }

        if (!$this->user = $this->model->get_user($uid))
            $this->error('User not found.', 404);

        $this->title = 'Reserver - Remove user';

        if (isset($_POST['remove_user'])) {
            $this->model->remove_user($uid);
            header('Location:' . URL . 'user/all');
        }

        $this->view('user', 'remove');
    }

    public function import()
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        if (!$this->model->get_permission('can_change_users')) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - Import users from Magister';

        if (isset($_POST['import_users'])) {
            $filename = $_POST['csv'];
            $this->model->import_from_magister($filename);
        }

        $this->view('user', 'import');
    }

    private function remove_account()
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        $uid = $_SESSION['logged_in'];

        $this->title = 'Reserver - Remove account';

        if (!$this->user = $this->model->get_user($uid))
            $this->error('User not found.', 404);

        if (isset($_POST['remove_account'])) {
            $password         = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            try {
                $this->model->remove_account($uid, $password, $confirm_password);
                $this->logout();
            } catch (Exception $e) {
                $this->error_message = 'Removing account failed: ' . $e->getMessage();
            }
        }

        $this->view('user', 'remove_account');
    }
}
