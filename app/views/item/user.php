    <div id="content">

        <div class="input-group" id="search">
            <input type="text" name="search_term" class="form-control" placeholder="Search for..." />
            <span class="input-group-btn">
                <input type="submit" class="btn btn-default" name="search_item" value="Search" />
            </span>
        </div>

        <?php if (!$this->model->get_all_items($_SESSION['logged_in'])): ?>
            <div class="alert alert-info">
                You haven't reserved any items (yet).
            </div>
        <?php else: ?>
            <div class="row">
            <?php foreach ($this->model->get_all_items($_SESSION['logged_in']) as $item): ?>
                <div class="col-sm-4 col-md-3">
                    <div class="thumbnail">
                        <h3>
                            <a href="<?= URL . "item/detail/$item->id" ?>">
                                <?= $item->name; ?>
                            </a>
                        </h3>
                        <p><?= $item->description; ?></p>

                        <form action="" method="post">
                        <input type="hidden" name="item_id" value="<?= $item->id ?>" />
                        <?php if ($item->available_count > 0): ?>
                            <?php if ($item->available_count < floor($item->count / 4)): ?>
                            <p class="alert alert-warning">
                                <?= $item->available_count ?> of the <?= $item->count ?> available.
                            </p>
                            <?php else: ?>
                            <p class="alert alert-success">
                                <?= $item->available_count ?> of the <?= $item->count ?> available.
                            </p>
                            <?php endif; ?>
                            <?php if ($this->permissions->can_reserve): ?>
                                <input type="submit" class="btn btn-success" name="reserve_item" value="Reserve" />
                                <input type="number" name="count" value="1" />
                            <?php elseif ($this->permissions->can_request): ?>
                                <input type="submit" class="btn btn-primary" name="request_item" value="Request" />
                                <input type="number" name="count" value="1" />
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="alert alert-danger">None available.</p>
                        <?php endif; ?>
                        </form>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
