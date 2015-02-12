    <div id="content">
        <form id="edit" action="" method="post">
            <h1>Edit user</h1>

            <input type="text" class="form-control" name="username" placeholder="Username" value="<?= $this->user->username ?>" />

            <input type="text" class="form-control" name="full_name" placeholder="Full Name" value="<?= $this->user->full_name ?>" />

            <input type="text" class="form-control" name="email" placeholder="Email address" value="<?= $this->user->email ?>" />

            <label for="access_group">Access group</label>
            <select name="access_group" class="form-control">

            <?php foreach ($this->model->get_all_access_groups() as $access_group): ?>
                <?php if ($access_group->name === $this->user->access_group): ?>
                    <option selected="selected" value="<?= $access_group->name ?>"><?= $access_group->name ?></option>
                <?php else: ?>
                    <option value="<?= $access_group->name ?>"><?= $access_group->name ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
            </select>
            <input type="submit" name="edit_user" class="btn btn-primary" value="Submit" />
        </form>
    </div>
</div>
