    <div id="content">
        <form id="edit" action="" method="post">
            <h1>Edit item</h1>

            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" placeholder="Item name" value="<?= $this->item->name ?>" />

            <label for="description">Description</label>
            <textarea name="description" rows="8" cols="44" placeholder="Description" ><?= $this->item->description ?></textarea>

            <label for="count">Count</label>
            <input type="number" class="form-control" name="count" placeholder="Count" value="<?= $this->item->count ?>" />

            <input type="submit" name="edit_item" class="btn btn-primary" value="Submit" />
        </form>
    </div>
</div>
