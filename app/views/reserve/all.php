    <div id="content">
        <h1>All reservations</h1>
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
                    <th>Returned</th>
                    <th>Count</th>
                    <th>Action</th>
                </thead>

                <tbody>
                    <?php foreach ($this->model->get_all_reservations() as $reservation): ?>
                    <tr>
                        <td><?= $reservation->id ?></td>
                        <td>
                            <a href="<?= URL . "item/detail/$reservation->item_id"?>">
                                <?= $this->model->get_item($reservation->item_id)->name ?>
                            </a>
                        </td>
                        <td><?= $this->model->get_user($reservation->user_id)->username ?></td>
                        <td><?= $reservation->reserved_at ?></td>
                        <td><?= $reservation->reserved_from ?></td>
                        <td><?= $reservation->reserved_to ?></td>
                        <td><?= $reservation->returned ?></td>
                        <td><?= $reservation->count ?></td>
                        <td>
                            &nbsp;
                            <a href="<?= URL . "reserve/edit/$reservation->id"?>">
                                <i class="fa fa-pencil"></i>
                            </a>
                            &nbsp;
                            <a href="<?= URL . "reserve/remove/$reservation->id"?>">
                                <span class="fa fa-trash"></span>
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
