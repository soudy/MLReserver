    <div id="content">
        <h1>Requests</h1>

        <?php if (!$this->model->get_all_requests($this->model->get_status_code(0))): ?>
            <div class="alert alert-info">
                There are no pending requests.
            </div>
        <?php else: ?>
            <div class="panel panel-default">
                <table class="table">

                    <thead>
                        <th>Id</th>
                        <th>Item</th>
                        <th>Count</th>
                        <th>User</th>
                        <th>Requested at</th>
                        <th>Requested from</th>
                        <th>Requested to</th>
                        <th>Hours</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach ($this->model->get_all_requests($this->model->get_status_code(0)) as $request): ?>
                        <tr>
                            <td><?= $request->id ?></td>
                            <td>
                                <a href="<?= URL . "item/detail/$request->item_id"?>">
                                    <?= $this->model->get_item($request->item_id)->name ?>
                                </a>
                            </td>
                            <td><?= $request->count ?></td>
                            <td><?= htmlspecialchars($this->model->get_user($request->user_id)->full_name) ?></td>
                            <td><?= $request->requested_at ?></td>
                            <td><?= $request->date_from ?></td>
                            <td><?= $request->date_to ?></td>
                            <td><?= $request->hours ?></td>
                            <td>
                            <?=
                                strlen($request->message) > 75
                                ? substr($request->message, 0, 75) . '...'
                                : $request->message
                            ?>
                            </td>
                            <td><?= ucfirst($this->model->get_status_code($request->status)) ?></td>
                            <td>
                                <?php if ($request->status): ?>
                                    &nbsp;
                                    <a href="<?= URL . "request/remove/$request->id"?>">
                                        <i class="fa fa-trash fa-lg"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= URL . "request/approve/$request->id"?>">
                                        <i class="fa fa-check fa-lg"></i>
                                    </a>
                                    &nbsp;
                                    <a href="<?= URL . "request/deny/$request->id"?>">
                                        <i class="fa fa-remove fa-lg"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
