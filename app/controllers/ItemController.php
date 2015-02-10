<?php
/*
 * MLReserver is a reservation system useful for sharing items in an honest way.
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

class ItemController extends MainController
{
    public function __construct()
    {
        if (!$_SESSION['logged_in'])
            header('Location: ' . URL . 'user/login');

        $this->model = new Item();

        $this->permissions = $this->model->get_user_permissions($_SESSION['logged_in']);
    }

    public function index()
    {
        $this->title = 'Reserver - All items';
        $this->view('item', 'all');
    }

    public function reserve($id, $count)
    {
        if (!($this->permissions->can_reserve || $id || $count)) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - Reserve items';
        $this->view('item', 'reserve');
    }

    public function request($id, $count)
    {
        if (!($this->permissions->can_request || $id || $count)) {
            $this->index();
            return false;
        }
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

    public function reserver($id, $count)
    {
        $this->title = 'Reserver - Reserve item';
        $this->view('item', 'reserve');
    }

    public function edit($id)
    {
        if (!($id || $this->permissions->can_change_items)) {
            $this->index();
            return false;
        }

        $this->item = $this->model->get_item($id);

        $this->title = 'Reserver - Edit item';
        $this->view('item', 'edit');

        // TODO: image uploading
        // TODO: input verifying
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
        if (!$this->permissions->can_change_items) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - Add item';
        $this->view('item', 'add');

        if (isset($_POST['add_item'])) {
            // TODO: image uploading
            // TODO: input verifying
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
        if (!($id || $this->permissions->can_change_items)) {
            $this->index();
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
