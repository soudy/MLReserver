    <div id="content">
        <h1>My reservations</h1>

        <?php if (!$this->model->get_reservations($_SESSION['logged_in'])): ?>
            <div class="alert alert-info">
                You have no requests.
            </div>
        <?php else: ?>
            <div class="panel panel-default">
                <table class="table">

                    <thead>
                        <th>Item</th>
                        <th>Count</th>
                        <th>Reserved at</th>
                        <th>Reserved from</th>
                        <th>Reserved to</th>
                        <th>Hours</th>
                        <th>Return item</th>
                    </thead>

                    <tbody>
                        <?php foreach ($this->model->get_reservations($_SESSION['logged_in']) as $reservation): ?>
                        <tr>
                            <td>
                                <a href="<?= URL . "item/detail/$reservation->item_id"?>">
                                    <?= $this->model->get_item($reservation->item_id)->name ?>
                                </a>
                            </td>
                            <td><?= $reservation->count ?></td>
                            <td><?= $reservation->reserved_at ?></td>
                            <td><?= $reservation->date_from ?></td>
                            <td><?= $reservation->date_to ?></td>
                            <td><?= $reservation->hours ?></td>
                            <td>
                                <input type="button" class="btn btn-success" value="Return" />
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
