    <div id="content">
        <h1>My requests</h1>

        <?php if (!$this->model->get_requests($_SESSION['logged_in'])): ?>
            <div class="alert alert-info">
                You have no requests.
            </div>
        <?php else: ?>
            <div class="panel panel-default">
                <table class="table">

                    <thead>
                        <th>Item</th>
                        <th>Count</th>
                        <th>Requested at</th>
                        <th>Requested from</th>
                        <th>Requested to</th>
                        <th>Hours</th>
                        <th>Message</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach ($this->model->get_requests($_SESSION['logged_in']) as $request): ?>
                        <tr>
                            <td>
                                <a href="<?= URL . "item/detail/$request->item_id"?>">
                                    <?= $this->model->get_item($request->item_id)->name ?>
                                </a>
                            </td>
                            <td><?= $request->count ?></td>
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
                            <td>
                                &nbsp;
                                <a href="<?= URL . "request/remove/$request->id"?>">
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
