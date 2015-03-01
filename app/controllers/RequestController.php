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

class RequestController extends MainController
{
    protected $model;

    public function __construct()
    {
        if (!isset($_SESSION['logged_in']))
            header('Location: ' . URL . 'user/login');

        $this->model = new Request();
    }

    public function index()
    {
        if ($this->model->get_permission('can_allow_requests'))
            $this->all();
        else
            header('Location: ' . URL);
    }

    public function request($id = null)
    {
        if (!$this->model->get_permission('can_request')) {
            $this->index();
            return false;
        }

        if (!$this->model->get_item($id))
            $this->error('Item not found.', 404);

        $this->item = $this->model->get_item($id);

        if (isset($_POST['request_item'])) {
            $user_id = $_SESSION['logged_in'];
            $item_id = $_POST['item'];
            $count   = $_POST['count'];
            $message = $_POST['message'];

            $date_format  = '%d-%d-%d';
            $hours_format = '%d-%d';

            $hours     = sprintf($hours_format, $_POST['hours_from'], $_POST['hours_to']);
            $date_from = sprintf($date_format, $_POST['day_from'], $_POST['month_from'],
                                 $_POST['year_from']);
            $date_to   = sprintf($date_format, $_POST['day_to'], $_POST['month_to'],
                                 $_POST['year_to']);

            try {
                $this->model->request_item($user_id, $item_id, $count, $date_from,
                                           $date_to, $hours, $message);
                $this->success_message = 'Your request has been successfully sent and is awaiting approval.';
            } catch (Exception $e) {
                $this->error_message = 'Failed to reserve item: ' . $e->getMessage();
            }
        }

        $this->title = 'Reserver - Request item';
        $this->view('request', 'request');
    }

    public function all($order = 'aid')
    {
        if (!$this->model->get_permission('can_allow_requests')) {
            $this->index();
            return false;
        }

        $this->order    = $order;
        $this->requests = $this->model->get_all_requests($order,
                                                         $this->model->get_status_code(0));

        $this->title = 'Reserver - Requests';
        $this->view('request', 'all');
    }

    public function user()
    {
        $this->title = 'Reserver - My requests';
        $this->view('request', 'user');

    }

    public function remove($id = null)
    {
        if (!$this->model->get_permission('can_allow_requests'))
            if (!$id || !$this->model->owns_request($_SESSION['logged_in'], $id)) {
                $this->index();
                return false;
            }

        if (!$this->request = $this->model->get_request($id))
            $this->error('Request not found.', 404);

        if (isset($_POST['remove_request'])) {
            try {
                $this->model->remove_request($id);
                header('Location: ' . URL . 'request/user');
            } catch (Exception $e) {
                $this->error_message = 'Failed to remove request: ' . $e->getMessage();
            }
        }

        $this->title = 'Reserver - Remove request';
        $this->view('request', 'remove');
    }

    public function approve($id = null)
    {
        if (!$id || !$this->model->get_permission('can_allow_requests')) {
            $this->index();
            return false;
        }

        if (!$request = $this->model->get_request($id)) {
            $this->error_message = "Request $id not found.";
            $this->all();
        }

        try {
            // Set status to approved (1).
            // See Model.php for all request status codes.
            $this->model->update_request_status($id, 1);

            $this->model->reserve_item($request->user_id, $request->item_id,
                                       $request->count, $request->date_from,
                                       $request->date_to, $request->hours);

            $this->success_message = 'Request successfully approved and reserved.';
        } catch (Exception $e) {
            $this->error_message = 'Failed to approve request: ' . $e->getMessage();
        }

        $this->title = 'Reserver - Requests';
        $this->view('request', 'all');
    }

    public function deny($id = null)
    {
        if (!$id || !$this->model->get_permission('can_allow_requests')) {
            $this->index();
            return false;
        }

        if (!$this->model->get_request($id)) {
            $this->error_message = "Request $id not found.";
            $this->all();
        }

        try {
            // Set status to denied (2).
            $this->model->update_request_status($id, 2);
            $this->success_message = 'Request successfully denied.';
        } catch (Exception $e) {
            $this->error_message = 'Failed to deny request: ' . $e->getMessage();
        }

        $this->all();
    }
}
