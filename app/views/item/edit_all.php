    <div id="content">
        <h1>Edit items</h1>
        <div class="panel panel-default">

        <table class="table">

            <thead>
                <th>Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Count</th>
                <th>Available</th>
                <th>Change</th>
            </thead>

            <tbody>
                <?php foreach ($this->model->get_all_items() as $item): ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td>
                        <a href="<?= URL . "item/detail/$item->id"?>">
                            <?= $item->name ?>
                        </a>
                    </td>
                    <td><?= $item->description ?></td>
                    <td><?= $item->image ?></td>
                    <td><?= $item->count ?></td>
                    <td><?= $item->available_count ?></td>
                    <td>
                        &nbsp;
                        <a href="<?= URL . "item/remove/$item->id"?>">
                            <span class="glyphicon glyphicon-trash"></span>
                        </a>
                        &nbsp;
                        <a href="<?= URL . "item/edit/$item->id"?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
