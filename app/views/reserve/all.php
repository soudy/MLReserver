    <div id="content">
        <h1>Reservations</h1>
        <?php if (!$this->reservations): ?>
            <div class="alert alert-info">
                There are no reserved items.
            </div>
        <?php else: ?>
            <div class="panel panel-default">
                <table class="table">

                    <thead>
                        <th>
                            <a href="<?= URL ?>reserve/all/<?= $this->order === 'did' ? 'aid' : 'did'?>">
                                Id
                            </a>
                        </th>
                        <th>Item</th>
                        <th>User</th>
                        <th>
                            <a href="<?= URL ?>reserve/all/<?= $this->order === 'dreserved_at' ? 'areserved_at' : 'dreserved_at'?>">
                                Reserved at
                            </a>
                        </th>
                        <th>
                            <a href="<?= URL ?>reserve/all/<?= $this->order === 'ddate_from' ? 'adate_from' : 'ddate_from'?>">
                                Reserved from
                            </a>
                        </th>
                        <th>
                            <a href="<?= URL ?>reserve/all/<?= $this->order === 'ddate_to' ? 'adate_to' : 'ddate_to'?>">
                                Reserved to
                            </a>
                        </th>
                        <th>
                            <a href="<?= URL ?>reserve/all/<?= $this->order === 'dhours' ? 'ahours' : 'dhours'?>">
                                Reserved hours
                            </a>
                        </th>
                        <th>
                            <a href="<?= URL ?>reserve/all/<?= $this->order === 'dcount' ? 'acount' : 'dcount'?>">
                                Count
                            </a>
                        </th>
                        <th>Action</th>
                    </thead>

                    <tbody>
                        <?php foreach ($this->reservations as $reservation): ?>
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
