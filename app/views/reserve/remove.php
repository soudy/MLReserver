    <div id="content">
        <form action="" method="post">
            <h1>Remove reservation</h1>
            <p>Are you sure you want to remove the following reservation? 
            Removing the reservation means the item(s) will be set available for reservation.</p>
            <table class="table">
                <thead>
                    <th>Id</th>
                    <th>Item</th>
                    <th>Count</th>
                    <th>Reserved at</th>
                    <th>Reserved from</th>
                    <th>Reserved to</th>
                    <th>Hours</th>
                </thead>

                <tbody>
                    <tr>
                        <td><?= $this->reservation->id ?></td>
                        <td>
                            <a href="<?= URL . 'item/detail/' . $this->reservation->item_id ?>">
                                <?= $this->model->get_item($this->reservation->item_id)->name ?>
                            </a>
                        </td>
                        <td><?= $this->reservation->count ?></td>
                        <td><?= $this->reservation->reserved_at ?></td>
                        <td><?= $this->reservation->date_from ?></td>
                        <td><?= $this->reservation->date_to ?></td>
                        <td><?= $this->reservation->hours ?></td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="remove_reservation" class="btn btn-danger" value="Remove reservation" />
            <input type="button" onclick="history.go(-1);" class="btn btn-normal" value="Go back" />
        </form>
    </div>
</div>
