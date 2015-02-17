    <div id="content">
        <div id="reserve">
            <h1>Requests</h1>

            <?php if (!$this->model->get_all_requests()): ?>
                <div class="alert alert-info">
                    There are no requests.
                </div>
            <?php else: ?>
                <div class="panel panel-default">
                    <table class="table">

                        <thead>
                            <th>Id</th>
                            <th>Item</th>
                            <th>User</th>
                            <th>Requested at</th>
                            <th>Requested from</th>
                            <th>Requested to</th>
                            <th>Status</th>
                            <th>Message</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            <?php foreach ($this->model->get_all_requests() as $request): ?>
                            <tr>
                                <td><?= $request->id ?></td>
                                <td>
                                    <a href="<?= URL . "item/detail/$request->item_id"?>">
                                        <?= $this->model->get_item($request->item_id)->name ?>
                                    </a>
                                </td>
                                <td><?= $this->model->get_user($request->user_id)->username ?></td>
                                <td><?= $request->requested_at ?></td>
                                <td>
                                    <?=
                                        /* Concatenate and format the date and time */
                                        $request->from_date . ' ' . $this->model->convert_from_school_time($request->from_hour)[0]
                                    ?>
                                </td>
                                <td>
                                    <?=
                                        $request->to_date . ' ' . $this->model->convert_from_school_time($request->to_hour)[1]
                                    ?>
                                </td>
                                <td><?= isset($request->status) ? 'awaiting' : $request->status ?></td>
                                <td>
                                <?=
                                    strlen($request->message) > 75
                                    ? substr($request->message, 0, 75) . '...'
                                    : $request->message
                                ?>
                                </td>
                                <td>
                                    <a href="<?= URL . "reserve/approve/$request->id"?>">
                                        <i class="fa fa-check fa-lg"></i>
                                    </a>
                                    &nbsp;
                                    <a href="<?= URL . "reserve/deny/$request->id"?>">
                                        <i class="fa fa-remove fa-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
