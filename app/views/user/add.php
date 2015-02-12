    <div id="content">
        <h1>Add user</h1>
        <form id="create" action="" method="post">
            <div class="well">
                <div class="alert alert-info">
                    The username and password will be generated and sent to the email address defined below.
                </div>
                <input type="text" class="form-control" name="full_name" placeholder="Full name" />

                <input type="text" class="form-control" name="email" placeholder="E-mail" />

                Access group:
                <select name="access_group" class="form-control">
                <?php foreach ($this->model->get_all_access_groups() as $access_group): ?>
                    <option value="<?= $access_group->name ?>"><?= $access_group->name ?></option>
                <?php endforeach; ?>
                </select>

                <input type="submit" name="add_user" class="btn btn-primary" value="Add user" />
            </div>
        </form>
    </div>
</div>
