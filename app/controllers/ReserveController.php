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

class ReserveController extends MainController
{
    protected $model;
    protected $permissions;

    public function __construct()
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        $this->model = new Reserve();
        $this->permissions = $this->model->get_user_permissions($_SESSION['logged_in']);
    }

    public function index()
    {
        if ($this->permissions->can_see_reservations) {
            $this->all();
            return true;
        } elseif ($this->permissions->can_reserve) {
            $this->reserve();
            return true;
        } else {
            $this->request();
            return true;
        }
    }

    public function all()
    {
        if (!$this->permissions->can_see_reservations) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - All reservations';
        $this->view('reserve', 'all');
    }

    public function reserve($id)
    {
        if (!$this->permissions->can_reserve) {
            $this->request();
            return false;
        }

        if (!$this->model->get_item($id)) {
            $this->error('Item not found.');
        }

        $this->item = $this->model->get_item($id);
        $this->title = 'Reserver - Reserve item';

        if (isset($_POST['reserve_item'])) {
            $user_id = $_SESSION['logged_in'];
            $item_id = $_POST['item'];
            $count   = $_POST['count'];

            $hours = '%d-%d';
            $hours = sprintf($hours, $_POST['hours_from'], $_POST['hours_to']);

            $date_from = '%d-%d-%d';
            $date_from = sprintf($date_from, $_POST['day_from'], $_POST['month_from'],
                                 $_POST['year_from']);

            $date_to = '%d-%d-%d';
            $date_to = sprintf($date_to, $_POST['day_to'], $_POST['month_to'],
                               $_POST['year_to']);

            try {
                $this->model->reserve_item($user_id, $item_id, $count, $date_from,
                                           $date_to, $hours);
            } catch (Exception $e) {
                $this->error_message = 'Failed to reserve item: ' . $e->getMessage();
            }
        }

        $this->view('reserve', 'reserve');
    }

    public function requests()
    {
        if (!$this->permissions->can_allow_requests) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - Requests';
        $this->view('reserve', 'requests');
    }

    public function request()
    {
        if (!$this->permissions->can_request) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - Request';
        $this->view('reserve', 'request');
    }

    public function approve($id = null)
    {
        if (!$id || !$this->permissions->can_allow_requests) {
            $this->index();
            return false;
        }
    }

    public function deny($id = null)
    {
        if (!$id || !$this->permissions->can_allow_requests) {
            $this->index();
            return false;
        }
    }

    public function detail($id)
    {
        if (!$id || !$this->permissions->can_allow_requests) {
            $this->index();
            return false;
        }

        $this->title = 'Reserver - Show reservation';
    }
}
