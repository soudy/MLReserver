    <div class="input-group" id="search">
        <input type="text" name="search_term" id="search_item" class="form-control" placeholder="Search for..." />
    </div>

    <div id="content" class="clear">
        <div class="row">
        <?php foreach ($this->model->get_all_items() as $item): ?>
            <div class="panel panel-default">
                <h4>
                    <a href="<?= URL . "item/detail/$item->id" ?>">
                        <?=
                            strlen($item->name) > 50
                            ? htmlspecialchars(substr($item->name, 0, 50) . '...')
                            : htmlspecialchars($item->name)
                        ?>
                    </a>
                </h4>
                <p>
                    <?=
                        // TODO: create a helper function for shortening
                        strlen($item->description) > 160
                        ? htmlspecialchars(substr($item->description, 0, 150) . '...')
                        : htmlspecialchars($item->description)
                    ?>
                </p>

                <input type="hidden" name="item_id" value="<?= $item->id ?>" />
                <?php if ($this->model->get_permission('can_reserve')): ?>
                    <a id="goto" href="<?= URL . 'reserve/reserve/' . $item->id?>">
                        Reserve item &raquo;
                    </a>
                <?php elseif ($this->model->get_permission('can_request')): ?>
                    <a id="goto" href="<?= URL . 'request/request/' . $item->id?>">
                        Request item &raquo;
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
