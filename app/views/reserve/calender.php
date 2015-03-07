    <div id="content">
        <?php if ($this->model->get_permission('can_see_reservations')): ?>
        <div class="btn-group">
            <a href="<?= URL . 'reserve/calender'?>">
                <button type="button" class="btn btn-default active" aria-label="Calender view">
                    <i class="fa fa-calendar-o fa-fw" alt="Calender view"></i>
                </button>
            </a>

            <a href="<?= URL . 'reserve/all'?>">
                <button type="button" class="btn btn-default" aria-label="Table view">
                    <i class="fa fa-table fa-fw" alt="Table view"></i>
                </button>
            </a>
        </div>
        <?php endif; ?>

        <div class="clear"></div>

        <h1>Reservations</h1>
        <hr />

        <?php if ($this->item_id): ?>
            <h3><?= $this->model->get_item($this->item_id)->name; ?></h3>
        <?php endif; ?>
        <h4><?= date('F', mktime(0, 0, 0, $this->month)) . ' ' . $this->year ?></h4>
        <?php for ($i = 1; $i <= $this->days_in_month; $i++): ?>
            <?php $date = sprintf('%d-%d-%d', $this->year, $this->month, $i); ?>
            <?=
                $i . ': ' . $this->model->get_reservation_count($this->item_id, $date) . '<br>';
            ?>
        <?php endfor; ?>

    </div>
</div>
