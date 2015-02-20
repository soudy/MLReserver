    <div id="content">
        <form action="" method="post" id="reserve">
            <h1>Request item</h1>

            <h3>Item</h3>
            <label for="item">Item</label>
            <select name="item" class="form-control">
            <?php foreach ($this->model->get_all_items() as $item): ?>
                <?php if ($item->id === $this->item->id): ?>
                    <option selected="selected" value="<?= $item->id ?>"><?= $item->name ?></option>
                <?php else: ?>
                    <option value="<?= $item->id?>"><?= $item->name ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
            </select>

            <label for="count">Count</label>
                <select name="count" class="form-control">
                    <?php for ($i = 1; $i <= $this->item->count; ++$i): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>

            <h3>Date</h3>
            <label for="date_from">From</label>
            <div id="date_from" class="date">
                <select name="day_from" class="form-control">
                    <?php for ($i = 1; $i <= date('t'); ++$i): ?>
                        <?php if ($i == date('j')): ?>
                            <option selected="selected"><?= $i ?></option>
                        <?php else: ?>
                            <option><?= $i ?></option>
                        <?php endif; ?>
                    <?php endfor; ?>
                </select>

                <select name="month_from" class="form-control">
                    <?php for ($i = 1; $i <= 12; ++$i): ?>
                        <?php if ($i == date('n')): ?>
                            <option value="<?= $i ?>" selected="selected">
                                <?= date('F', mktime(0, 0, 0, $i, 1, date('Y'))); ?>
                            </option>
                        <?php else: ?>
                            <option value="<?= $i ?>">
                                <?= date('F', mktime(0, 0, 0, $i, 1, date('Y'))); ?>
                            </option>
                        <?php endif; ?>
                    <?php endfor; ?>
                </select>

                <select name="year_from" class="form-control">
                    <option selected="selected"><?= date('Y') ?></option>
                    <option><?= date('Y') + 1 ?></option>
                </select>
            </div>

            <div class="clear"></div>

            <label for="date_to">To</label>
            <div id="date_to" class="date" >
                <select name="day_to" class="form-control">
                    <?php for ($i = 1; $i <= date('t'); ++$i): ?>
                        <?php if ($i == date('j')): ?>
                            <option selected="selected"><?= $i ?></option>
                        <?php else: ?>
                            <option><?= $i ?></option>
                        <?php endif; ?>
                    <?php endfor; ?>
                </select>

                <select name="month_to" class="form-control">
                    <?php for ($i = 1; $i <= 12; ++$i): ?>
                        <?php if ($i == date('n')): ?>
                            <option value="<?= $i ?>" selected="selected">
                                <?=
                                    date('F', mktime(0, 0, 0, $i, 1, date('Y')));
                                ?>
                            </option>
                        <?php else: ?>
                            <option value="<?= $i ?>">
                                <?=
                                    date('F', mktime(0, 0, 0, $i, 1, date('Y')));
                                ?>
                            </option>
                        <?php endif; ?>
                    <?php endfor; ?>
                </select>

                <select name="year_to" class="form-control">
                    <option name="<?= date('Y') ?>" selected="selected"><?= date('Y') ?></option>
                    <option name="<?= date('Y') + 1 ?>"><?= date('Y') + 1 ?></option>
                </select>
            </div>

            <h3>Hours</h3>
            <div id="hours" class="hours">
                <label for="hours_from">From</label>
                <select name="hours_from" class="form-control">
                    <?php for($i = 1; $i <= 8; ++$i): ?>
                        <option><?= $i ?></option>
                    <?php endfor; ?>
                </select>

                <label for="hours_to">To</label>
                <select name="hours_to" class="form-control">
                    <?php for($i = 2; $i <= 8; ++$i): ?>
                        <option><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <h3>Message</h3>
            <textarea name="message" placeholder="Why do you want this item?" rows="8" cols="40"></textarea>

            <div class="clear"></div>

            <input type="submit" name="request_item" class="btn btn-primary" value="Request" />
        </form>
    </div>
</div>
