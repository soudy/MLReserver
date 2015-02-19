    <div id="content">
        <form id="remove" action="" method="post">
            <h1>Remove item</h1>
            <p>Are you sure you want to remove the item "<?= htmlspecialchars($this->item->name); ?>"?
            This cannot be undone.</p>
            <input type="submit" name="remove_item" class="btn btn-danger" value="Remove item" />
            <input type="button" onclick="history.go(-1);" class="btn btn-normal" value="Go back" />
        </form>
    </div>
</div>
