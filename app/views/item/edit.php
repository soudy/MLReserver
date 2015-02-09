    <div id="content">
        <form id="edit" action="" method="post">
            <h1>Edit item</h1>
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="Item name" value="<?= $this->item->name ?>" />
            <label for="description">Description</label>
            <textarea name="description" rows="8" cols="40" placeholder="Description" ><?= $this->item->description ?></textarea>
            <label for="image">Image</label>
            <input type="text" name="image" placeholder="Image" value="<?= $this->item->image ?>" />
            <label for="count">Count</label>
            <input type="number" name="count" placeholder="Count" value="<?= $this->item->count ?>" />
            <input type="submit" name="edit_item" value="Edit item" />
        </form>
    </div>
</div>
