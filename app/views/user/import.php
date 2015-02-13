    <div id="content">
        <h1>Import from Magister</h1>
        <form id="create" action="" method="post">
            <div class="alert alert-info">
            </div>
            
            <label for="full_name">CSV file</label>
            <input type="file" class="form-control" name="csv" accept=".csv" />

            <label for="full_name">Access group</label>
            <select name="access_group" class="form-control">
            <?php foreach ($this->model->get_all_access_groups() as $access_group): ?>
                <option value="<?= $access_group->name ?>"><?= $access_group->name ?></option>
            <?php endforeach; ?>
            </select>

            <input type="submit" name="import_users" class="btn btn-primary" value="Import" />
        </form>
    </div>
</div>
