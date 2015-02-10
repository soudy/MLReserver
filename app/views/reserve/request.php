    <div id="content">
        <h1>Request item</h1>
        <div class="panel panel-default">

        <table class="table">

            <thead>
                <th>Id</th>
                <th>Item</th>
                <th>User</th>
                <th>Requested at</th>
                <th>Requested from</th>
                <th>Requested to</th>
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
                    <td><?= $this->model->get_user($request->user_id) ?></td>
                    <td><?= $request->requested_at ?></td>
                    <td>
                        <?=
                            /* Concatenate and format the date and time */
                            $request->from_date . ' ' . $this->model->convert_from_school_time($request->from_date)
                        ?>
                    </td>
                    <td>
                        <?=
                            /* Concatenate and format the date and time */
                            $request->to_date . ' ' . $this->model->convert_from_school_time($request->to_date)
                        ?>
                    </td>
                    <td><?= $request->message ?></td>
                    <td>Approve / Deny</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
