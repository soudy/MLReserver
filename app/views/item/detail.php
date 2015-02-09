    <div id="content">
    <a href="#" onclick="history.go(-1);">&laquo; Back</a>
        <h1><?= $this->item->name; ?></h1>
        <p><?= $this->item->description ?></p>
        <?php if ($this->item->available_count > 0): ?>
            <?php if ($this->item->available_count < floor($this->item->count / 4)): ?>
            <p class="alert alert-warning">
                <?= $this->item->available_count ?> of the <?= $this->item->count ?> available.
            </p>
            <?php else: ?>
            <p class="alert alert-success">
                <?= $this->item->available_count ?> of the <?= $this->item->count ?> available.
            </p>
            <?php endif; ?>
            <input type="submit" class="btn btn-success" name="reserve_item" value="Reserve" />
            <input type="number" name="count" value="1" />
        <?php else: ?>
            <p class="alert alert-danger">None available.</p>
        <?php endif; ?>

    </div>
</div>
