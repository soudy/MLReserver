<?php

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
            if (!($username || $password)) {
                echo 'Please fill in all fields.';
                return false;
            }

            if ($this->model->log_in($username, $password, $stay_logged_in)) {
                header('Location: ' . URL . 'item');
            } else {
                echo 'Invalid username or password';
                return false;
            }
        }
    }

    public function settings()
    {
        if (!$_SESSION['logged_in'])
            $this->error('You need to be signed in to come here.');

        $this->user = $this->model->get_info($_SESSION['logged_in']);

        $this->title = 'Reserver - Settings';
        $this->view('user', 'settings');
    }

    public function logout()
    {
        $this->model->logout();
    }

    public function add()
    {
        if (!$_SESSION['logged_in'])
            $this->error('You need to be signed in to come here.');

        if (!$this->permissions->can_change_users)
            $this->error('You don\'t have the permissions to come here.');

        $this->title = 'Reserver - Add user';
        $this->view('user', 'add');

        // TODO: check for valid email and full name
        if (isset($_POST['add_user'])) {
            $full_name    = $_POST['full_name'];
            $email        = $_POST['email'];
            $access_group = $_POST['access_group'];

            if ($this->model->add_user($full_name, $email, $access_group))
                echo "User '$full_name' added.";
            else
                echo "User '$full_name' already exists in database.";
        }
    }

    public function all($uid)
    {
        if (!$this->permissions->can_change_users)
            $this->error('You don\'t have the permissions to come here.');

        $this->title = 'Reserver - Edit users';
        $this->view('user', 'all');

    }

    public function remove($uid)
    {
        if (!$this->permissions->can_change_users)
            $this->error('You don\'t have the permissions to come here.');

        if (!$uid) {
            $this->all();
            return false;
        }

        if (!$this->user = $this->model->get_info($uid))
            $this->error('User not found.');

        $this->title = 'Reserver - Remove user';
        $this->view('user', 'remove');

        if (isset($_POST['remove_user'])) {
            $this->model->remove_user($uid);
            header('Location:' . URL . 'user/all');
        }
    }
}
