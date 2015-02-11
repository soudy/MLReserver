    <div id="content">
        <form id="edit" action="" method="post">
            <h1>Edit user</h1>
            <label for="name">Username</label>
            <input type="text" name="username" placeholder="Username" value="<?= $this->user->username ?>" />
            <label for="full_name">Full name</label>
            <input type="text" name="full_name" placeholder="Full Name" value="<?= $this->user->full_name ?>" />
            <label for="email">Email</label>
            <input type="text" name="email" placeholder="Email address" value="<?= $this->user->email ?>" />
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
