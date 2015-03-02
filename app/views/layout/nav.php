<nav>
    <ul class="nav nav-tabs nav-justified">
        <li id="all_items" class="active">
            <a href="<?= URL . 'item/'?>">All items</a>
        </li>

        <?php if ($this->model->get_reservations($_SESSION['logged_in'])): ?>
        <li id="my_reservations">
            <a href="<?= URL . 'reserve/user'?>">My reservations</a>
        </li>
        <?php endif; ?>

        <?php if ($this->model->get_requests($_SESSION['logged_in'])): ?>
        <li id="my_requests">
            <a href="<?= URL . 'request/user'?>">My requests</a>
        </li>
        <?php endif; ?>

        <?php if ($this->model->get_permission('can_change_users')): ?>
        <li id="items" class="dropdown">
            <a href="<?= URL . 'item/all'?>">Items<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="<?= URL . 'item/add'?>">Add item</a>
                </li>
                <li>
                    <a href="<?= URL . 'item/all'?>">Edit items</a>
                </li>
            </ul>
        </li>
        <?php endif; ?>

        <?php if ($this->model->get_permission('can_change_items')): ?>
        <li id="users" class="dropdown">
            <a href="<?= URL . 'user/all'?>">Users<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="<?= URL . 'user/add'?>">Add user</a>
                </li>
                <li>
                    <a href="<?= URL . 'user/all'?>">Edit users</a>
                </li>
                <li>
                    <a href="<?= URL . 'user/import'?>">Import from Magister</a>
                </li>
            </ul>
        </li>
        <?php endif; ?>

        <?php if ($this->model->get_permission('can_allow_requests') && $this->model->get_all_requests()): ?>
        <li id="all_requests">
            <a href="<?= URL . 'request/all'?>">
                Requests
                <?php if (sizeof($this->model->get_all_requests(null, $this->model->get_status_code(0))) > 0): ?>
                <span class="badge">
                    <?= sizeof($this->model->get_all_requests(null, $this->model->get_status_code(0))) ?>
                </span>
                <?php endif; ?>
            </a>
        </li>
        <?php endif; ?>

        <?php if ($this->model->get_permission('can_see_reservations')): ?>
        <li id="all_reservations">
            <a href="<?= URL . 'reserve/all'?>">Reservations</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>

<?php if (isset($this->error_message)): ?>
    <div id="error-msg">
        <div class="alert alert-danger">
            <?= $this->error_message ?>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($this->success_message)): ?>
    <div id="success-msg">
        <div class="alert alert-success">
            <?= $this->success_message ?>
        </div>
    </div>
<?php endif; ?>
