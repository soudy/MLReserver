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

class ItemController extends MainController
{
    protected $model;

    public function __construct()
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        $this->model = new Item();
    }

    public function index()
    {
        $this->title = 'Reserver - All items';
        $this->view('item', 'all');
    }

    public function all($order = 'aname')
    {
        if (!$this->model->get_permission('can_change_items')) {
            $this->index();
            return false;
        }

        $this->order = $order;
        $this->items = $this->model->get_all_items($order);

        $this->title = 'Reserver - Edit items';
        $this->view('item', 'edit_all');
    }

    public function edit($id = null)
    {
        if (!$id || !$this->model->get_permission('can_change_items')) {
            $this->index();
            return false;
        }

        if (!($this->item = !$this->model->get_item($id)))
            $this->error('Item not found.', 404);

        $this->title = 'Reserver - Edit item';

        if (isset($_POST['edit_item'])) {
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $count       = $_POST['count'];

            try {
                $this->model->edit_item($id, $name, $description, $count);
                header('Location: ' . URL . "item/all#$id");
            } catch (Exception $e) {
                $this->error_message = 'Adding item failed: ' . $e->getMessage();
            }
        }

        $this->view('item', 'edit');
    }

    public function search($query)
    {
        $this->title = 'Reserver - Search';

        echo json_encode($this->model->search($query));
    }

    public function detail($id = null)
    {
        if (!($this->item = $this->model->get_item($id)))
            $this->error('Item not found.', 404);

        $this->title = 'Reserver - ' . $this->item->name;
        $this->view('item', 'detail');
    }

    public function add()
    {
        if (!$this->model->get_permission('can_change_items')) {
            $this->index();
            return false;
        }

        if (isset($_POST['add_item'])) {
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $count       = $_POST['count'];

            try {
                $this->model->add_item($name, $description, $count);
                $this->success_message = 'Item successfully created.';
                header('Location: ' . URL . "item/all");
            } catch (Exception $e) {
                $this->error_message = 'Adding item failed: ' . $e->getMessage();
            }
        }

        $this->title = 'Reserver - Add item';
        $this->view('item', 'add');
    }

    public function remove($id = null)
    {
        if (!$id || !$this->model->get_permission('can_change_items')) {
            $this->index();
            return false;
        }

        if (!($this->item = $this->model->get_item($id)))
            $this->error('Item not found.', 404);

        if (isset($_POST['remove_item'])) {
            $this->model->remove_item($id);
            $this->success_message = 'Item ' . $this->model->get_item($id)->name . ' successfully removed.';
            header('Location:' . URL . 'item/all');
        }

        $this->title = 'Reserver - Remove item';
        $this->view('item', 'remove');
    }
}
