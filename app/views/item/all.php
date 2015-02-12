    <div class="input-group" id="search">
        <input type="text" name="search_term" class="form-control" placeholder="Search for..." />
        <span class="input-group-btn">
            <input type="submit" class="btn btn-default" name="search_item" value="Search" />
        </span>
    </div>

    <div id="content" class="clear">
        <div class="row">
        <?php foreach ($this->model->get_all_items() as $item): ?>
            <div class="panel panel-default">
                    <h4>
                        <a href="<?= URL . "item/detail/$item->id" ?>">
                            <?=
                                strlen($item->name) > 50
                                ? substr($item->name, 0, 50) . '...'
                                : $item->name ?>
                        </a>
                    </h4>
                    <p>
                        <?= strlen($item->description) > 160
                            ? substr($item->description, 0, 150) . '...'
                            : $item->description ?>
                    </p>

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
                            <a id="goto" href="<?= URL . 'reserve/reserve/' . $item->id?>">
                                Reserve item &raquo;
                            </a>
                        <?php elseif ($this->permissions->can_request): ?>
                            <a id="goto" href="<?= URL . 'reserve/request/' . $item->id?>">
                                Request item &raquo;
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="alert alert-danger">None available.</p>
                    <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
