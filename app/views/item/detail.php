    <div id="content">
    <a href="#" onclick="history.go(-1);">&laquo; Back</a>
        <form id="detail" action="" method="post">
            <h1><?= $this->item->name; ?></h1>
            <p><?= $this->item->description ?></p>
        </form>
        <?php if($this->permissions->can_reserve): ?>
            <a href="<?= URL . 'reserve/reserve/' . $this->item->id?>">
                <input type="button" class="btn btn-success" value="Reserve &raquo;" />
            </a>
        <?php elseif($this->permissions->can_request): ?>
            <a href="<?= URL . 'reserve/request/' . $this->item->id?>">
                <input type="button" class="btn btn-primary" value="Request &raquo;" />
            </a>
        <?php endif; ?>
    </div>
</div>
