    <div id="content">
        <h1>Edit users</h1>
        <div class="panel panel-default">

        <table class="table">

            <thead>
                <th>Id</th>
                <th>Username</th>
                <th>Email</th>
                <th>Full name</th>
                <th>Access group</th>
                <th>Change</th>
            </thead>

            <tbody>
                <?php foreach ($this->model->get_all_users() as $user): ?>
                <tr>
                    <td id="<?= $user->id ?>"><?= $user->id ?></td>
                    <td><?= $user->username ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= $user->full_name ?></td>
                    <td><?= $user->access_group ?></td>
                    <td>
                        &nbsp;
                        <a href="<?= URL . "user/remove/$user->id"?>">
                            <i class="fa fa-trash fa-lg"></i>
                        </a>
                        &nbsp;
                        <a href="<?= URL . "user/edit/$user->id"?>">
                            <i class="fa fa-pencil fa-lg"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
