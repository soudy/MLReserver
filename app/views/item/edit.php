    <div id="content">
    <a href="#" onclick="history.go(-1);">&laquo; Back</a>
        <form id="edit" action="" method="post">
            <h1>Edit item</h1>

            <input type="text" class="form-control" name="name" placeholder="Item name" value="<?= $this->item->name ?>" />

            <textarea class="form-control" name="description" placeholder="Description" ><?= $this->item->description ?></textarea>

            <label for="count">Count</label>
            <input type="number" class="form-control" name="count" placeholder="Count" value="<?= $this->item->count ?>" />

            <input type="submit" name="edit_item" class="btn btn-primary" value="Submit" />
        </form>
    </div>
</div>
