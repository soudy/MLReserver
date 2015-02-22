<?php
/**
 * Short description for RequestController.php
 *
 * @package RequestController
 * @author soud
 * @version 0.1
 * @copyright (C) 2015 soud
 * @license MIT
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
        $this->title = 'Reserver - Request item';

        if (isset($_POST['request_item'])) {
            $user_id = $_SESSION['logged_in'];
            $item_id = $_POST['item'];
            $count   = $_POST['count'];
            $message = $_POST['message'];

            $hours = '%d-%d';
            $hours = sprintf($hours, $_POST['hours_from'], $_POST['hours_to']);

            $date_from = '%d-%d-%d';
            $date_from = sprintf($date_from, $_POST['day_from'], $_POST['month_from'],
                                 $_POST['year_from']);

            $date_to = '%d-%d-%d';
            $date_to = sprintf($date_to, $_POST['day_to'], $_POST['month_to'],
                               $_POST['year_to']);

            try {
                $this->model->request_item($user_id, $item_id, $count, $date_from,
                                           $date_to, $hours, $message);
            } catch (Exception $e) {
                $this->error_message = 'Failed to reserve item: ' . $e->getMessage();
            }
        }

        $this->view('request', 'request');
    }

    public function all()
    {
        if (!$this->model->get_permission('can_allow_requests')) {
            $this->index();
            return false;
        }

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
        if (!$id || !$this->model->owns_request($_SESSION['logged_in'], $id)) {
            $this->index();
            return false;
        }

        if (!$this->request = $this->model->get_request($id))
            $this->error('Request not found.', 404);

        $this->title   = 'Reserver - Remove request';
        $this->view('request', 'remove');
    }

    public function approve($id = null)
    {
        if (!$id || !$this->model->get_permission('can_allow_requests')) {
            $this->index();
            return false;
        }
    }

    public function deny($id = null)
    {
        if (!$id || !$this->model->get_permission('can_allow_requests')) {
            $this->index();
            return false;
        }
    }
}
