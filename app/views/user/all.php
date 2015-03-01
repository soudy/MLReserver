    <div id="content">
        <h1>Edit users</h1>
        <div class="panel panel-default">

        <table class="table">

            <thead>
                <th>
                    <a href="<?= URL ?>user/all/<?= $this->order === 'did' ? 'aid' : 'did'?>">
                        Id
                    </a>
                </th>
                <th>
                    <a href="<?= URL ?>user/all/<?= $this->order === 'dusername' ? 'ausername' : 'dusername'?>">
                        Username
                    </a>
                </th>
                <th>
                    <a href="<?= URL ?>user/all/<?= $this->order === 'demail' ? 'aemail' : 'demail'?>">
                        Email
                    </a>
                </th>
                <th>
                    <a href="<?= URL ?>user/all/<?= $this->order === 'dfull_name' ? 'afull_name' : 'dfull_name'?>">
                        Full name
                    </a>
                </th>
                <th>
                    <a href="<?= URL ?>user/all/<?= $this->order === 'aaccess_group' ? 'daccess_group' : 'aaccess_group'?>">
                        Access group
                    </a>
                </th>
                <th>Change</th>
            </thead>

            <tbody>
                <?php foreach ($this->users as $user): ?>
                <tr>
                    <td id="<?= $user->id ?>"><?= $user->id ?></td>
                    <td><?= htmlspecialchars($user->username) ?></td>
                    <td><?= htmlspecialchars($user->email) ?></td>
                    <td><?= htmlspecialchars($user->full_name) ?></td>
                    <td><?= $user->access_group ?></td>
                    <td>
                        &nbsp;
                        <a href="<?= URL . "user/edit/$user->id"?>">
                            <i class="fa fa-pencil fa-lg"></i>
                        </a>
                        &nbsp;
                        <a href="<?= URL . "user/remove/$user->id"?>">
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
