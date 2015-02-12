    <div id="content">
        <form action="" method="post" class="input-group">
            <h1>Request item</h1>

            <label for="items">Item</label>
            <select name="items" class="form-control">
            <?php foreach ($this->model->get_all_items() as $item): ?>
                <?php if ($item->id === $this->item->id): ?>
                    <option selected="selected" name="<?= $item->id ?>"><?= $item->name ?></option>
                <?php else: ?>
                    <option name="<?= $item->id?>"><?= $item->name ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>
