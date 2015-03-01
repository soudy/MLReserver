    <div id="content">
        <h1>Edit items</h1>
        <div class="panel panel-default">

        <table class="table">

            <thead>
                <th>
                    <a href="<?= URL ?>item/all/<?= $this->order === 'aid' ? 'did' : 'aid'?>">
                        Id
                    </a>
                </th>
                <th>
                    <a href="<?= URL ?>item/all/<?= $this->order === 'dname' ? 'aname' : 'dname'?>">
                        Name
                    </a>
                </th>
                <th>
                    <a href="<?= URL ?>item/all/<?= $this->order === 'adescription' ? 'ddescription' : 'adescription'?>">
                        Description
                    </a>
                </th>
                <th>
                    <a href="<?= URL ?>item/all/<?= $this->order === 'acount' ? 'dcount' : 'acount'?>">
                        Count
                    </a>
                </th>
                <th>Change</th>
            </thead>

            <tbody>
                <?php foreach ($this->items as $item): ?>
                <tr>
                    <td id="<?= $item->id ?>"><?= $item->id ?></td>
                    <td>
                        <a href="<?= URL . "item/detail/$item->id"?>">
                            <?= htmlspecialchars($item->name) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($item->description) ?></td>
                    <td><?= $item->count ?></td>
                    <td>
                        &nbsp;
                        <a href="<?= URL . "item/edit/$item->id"?>">
                            <i class="fa fa-pencil fa-lg"></i>
                        </a>
                        &nbsp;
                        <a href="<?= URL . "item/remove/$item->id"?>">
                            <i class="fa fa-trash fa-lg"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
