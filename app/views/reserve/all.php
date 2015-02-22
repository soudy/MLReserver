    <div id="content">
        <h1>Reservations</h1>
        <?php if (!$this->model->get_all_reservations()): ?>
            <div class="alert alert-info">
                There are no reserved items.
            </div>
        <?php else: ?>
            <div class="panel panel-default">
                <table class="table">

                    <thead>
                        <th>Id</th>
                        <th>Item</th>
                        <th>User</th>
                        <th>Reserved at</th>
                        <th>Reserved from</th>
                        <th>Reserved to</th>
                        <th>Reserved hours</th>
                        <th>Count</th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach ($this->model->get_all_reservations() as $reservation): ?>
                        <tr>
                            <td><?= $reservation->id ?></td>
                            <td>
                                <a href="<?= URL . "item/detail/$reservation->item_id"?>">
                                    <?= htmlspecialchars($this->model->get_item($reservation->item_id)->name) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($this->model->get_user($reservation->user_id)->username) ?></td>
                            <td><?= $reservation->reserved_at ?></td>
                            <td><?= $reservation->date_from ?></td>
                            <td><?= $reservation->date_to ?></td>
                            <td><?= $reservation->hours ?></td>
                            <td><?= $reservation->count ?></td>
                            <td>
                                &nbsp;
                                <a href="<?= URL . "reserve/edit/$reservation->id"?>">
                                    <i class="fa fa-pencil fa-lg"></i>
                                </a>
                                &nbsp;
                                <a href="<?= URL . "reserve/remove/$reservation->id"?>">
                                    <span class="fa fa-trash fa-lg"></span>
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
