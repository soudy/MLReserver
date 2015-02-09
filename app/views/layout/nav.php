<div id="dashboard">
    <nav>
        <ul class="nav nav-tabs">
            <li id="all" class="active">
                <a href="<?= URL . 'item/'?>">All items</a>
            </li>

            <li id="user">
                <a href="<?= URL . 'item/user'?>">My items</a>
            </li>

            <li id="request">
                <a href="<?= URL . 'request/all'?>">Requests <span class="badge">11</span></a>
            </li>

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
        </ul>
    </nav>
</div>
