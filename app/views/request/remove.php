    <div id="content">
        <form action="" method="post">
            <h1>Remove request</h1>
            <p>Are you sure you want to remove the following request?</p>
            <table class="table">
                <thead>
                    <th>Item</th>
                    <th>Count</th>
                    <th>Requested at</th>
                    <th>Requested from</th>
                    <th>Requested to</th>
                    <th>Hours</th>
                    <th>Status</th>
                    <th>Message</th>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <a href="<?= URL . 'item/detail/' . $this->request->item_id ?>">
                                <?= $this->model->get_item($this->request->item_id)->name ?>
                            </a>
                        </td>
                        <td><?= $this->request->count ?></td>
                        <td><?= $this->request->requested_at ?></td>
                        <td><?= $this->request->date_from ?></td>
                        <td><?= $this->request->date_to ?></td>
                        <td><?= $this->request->hours ?></td>
                        <td><?= ucfirst($this->model->get_status_code($this->request->status)) ?></td>
                        <td>
                        <?=
                            strlen($this->request->message) > 75
                            ? substr($this->request->message, 0, 75) . '...'
                            : $this->request->message
                        ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="remove_request" class="btn btn-danger" value="Remove request" />
            <input type="button" onclick="history.go(-1);" class="btn btn-normal" value="Go back" />
        </form>
    </div>
</div>
