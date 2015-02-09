<?php

class ItemController extends MainController
{
    public function __construct()
    {
        if (!$_SESSION['logged_in'])
            header('Location: ' . URL . 'user/login');

        /* $this->permissions = $this->model->get_user_permissions($_SESSION['logged_in']); */
        $this->model = new Item();
        $this->user  = new User();
        $this->permissions = $this->user->get_user_permissions($_SESSION['logged_in']);
    }

    public function index()
    {
        $this->title = 'Reserver - All items';
        $this->view('item', 'all');
    }

    public function all()
    {
        $this->title = 'Reserver - Edit items';
        $this->view('item', 'edit_all');
    }

    public function user()
    {
        $this->title = 'Reserver - My items';
        $this->view('item', 'all');
    }

    public function edit($id)
    {
        if (!$id) {
            $this->index();
            return false;
        }

        $this->item = $this->model->get_item($id);

        $this->title = 'Reserver - Edit item';
        $this->view('item', 'edit');

        // TODO: image uploading
        if (isset($_POST['edit_item'])) {
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $image       = $_POST['image'];
            $count       = $_POST['count'];

            if ($this->model->edit_item($id, $name, $description, $image, $count))
                header('Location: ' . URL . 'item/all');

        }
    }

    public function detail($id)
    {
        $this->item = $this->model->get_item($id);

        if (!$this->item)
            $this->error('Item not found.');

        $this->title = 'Reserver - ' . $this->item->name;
        $this->view('item', 'detail');
    }

    public function add()
    {
        $this->title = 'Resever - Add item';
        $this->view('item', 'add');

        if (isset($_POST['add_item'])) {
            // TODO: image uploading
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $count       = $_POST['count'];

            if (!($name || $description || $count)) {
                echo 'Please fill in all fields.';
                return false;
            }

            $this->model->add_item($name, $description, $count);
            echo 'Item successfully added.';
        }
    }

    public function remove($id)
    {
        if (!$id) {
            $this->all();
            return false;
        }

        if (!$this->item = $this->model->get_item($id))
            $this->error('Item not found.');

        $this->title = 'Reserver - Remove item';
        $this->view('item', 'remove');

        if (isset($_POST['remove_item'])) {
            $this->model->remove_item($id);
            header('Location:' . URL . 'item/all');
        }
    }
}
