<div class="container">
    <header>
        <div id="menu">
            <div id="user">
                Logged in as <strong><?= $this->model->get_user($_SESSION['logged_in'])->username ?></strong>
            </div>
            <ul>
                <li>
                    <i class="fa fa-wrench"></i>
                    <a href="<?= URL . 'user/settings'?>">Settings</a>
                </li>
                <li>
                    <i class="fa fa-sign-out "></i>
                    <a href="<?= URL . 'user/logout'?>">Log out</a>
                </li>
            </ul>
        </div>

        <div class="clear"></div>
    </header>

