<nav>
    <ul class="nav nav-tabs nav-justified">
        <li id="all" class="active">
            <a href="<?= URL . 'item/'?>">All items</a>
        </li>

        <?php if ($this->model->get_reservations($_SESSION['logged_in'])): ?>
        <li id="user">
            <a href="<?= URL . 'item/user'?>">My reservations</a>
        </li>
        <?php endif; ?>

        <?php if ($this->model->get_requests($_SESSION['logged_in'])): ?>
        <li id="user">
            <a href="<?= URL . 'item/requests'?>">My requests</a>
        </li>
        <?php endif; ?>

        <?php if ($this->model->get_permission('can_allow_requests')): ?>
        <li id="request">
            <a href="<?= URL . 'reserve/requests'?>">Requests <span class="badge">11</span></a>
        </li>
        <?php endif; ?>

        <?php if ($this->model->get_permission('can_change_users')): ?>
        <li id="item" class="dropdown">
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
        <li id="item" class="dropdown">
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

        <?php if ($this->model->get_permission('can_see_reservations')): ?>
        <li id="item">
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
