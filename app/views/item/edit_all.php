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
                    <td id="<?= $item->id ?>"><?= $item->id ?></td>
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
                            <i class="fa fa-trash"></i>
                        </a>
                        &nbsp;
                        <a href="<?= URL . "item/edit/$item->id"?>">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
