<div id="dashboard">
    <nav>
        <ul class="nav nav-tabs">
            <li id="all" class="active">
                <a href="<?= URL . 'item/'?>">All items</a>
            </li>

            <?php if ($this->model->get_all_items($_SESSION['logged_in'])): ?>
            <li id="user">
                <a href="<?= URL . 'item/user'?>">My reservations</a>
            </li>
            <?php endif; ?>

            <?php if ($this->permissions->can_allow_requests): ?>
            <li id="request">
                <a href="<?= URL . 'reserve/requests'?>">Requests <span class="badge">11</span></a>
            </li>
            <?php endif; ?>

            <?php if ($this->permissions->can_change_users): ?>
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

            <?php if ($this->permissions->can_change_items): ?>
            <li id="item" class="dropdown">
                <a href="<?= URL . 'user/all'?>">Users<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="<?= URL . 'user/add'?>">Add user</a>
                    </li>
                    <li>
                        <a href="<?= URL . 'user/all'?>">Edit users</a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($this->permissions->can_allow_requests): ?>
            <li id="item" class="dropdown">
                <a href="<?= URL . 'reserve/all'?>">Reservations<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="<?= URL . 'reserve/all'?>">All reservations</a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
