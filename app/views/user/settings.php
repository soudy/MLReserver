    <div id="content">
        <form id="settings" action="" method="post">
            <h1>Settings</h1>
            <h3>General</h3>
            <label for="email">Email</label>
            <input type="text" class="form-control" name="email" placeholder="Email address" value="<?= $this->user->email ?>" />

            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" value="<?= $this->user->username ?>" />

            <label for="send_reminders">
                Send reminders
            </label>
            <?php if ($this->user->send_reminders): ?>
                <input type="checkbox" name="send_reminders" checked="checked" />
            <?php else: ?>
                <input type="checkbox" name="send_reminders" />
            <?php endif; ?>

            <h3>Password</h3>
            <input type="password" class="form-control" name="current_password" placeholder="Current Password" />

            <input type="password" class="form-control" name="new_password" placeholder="New Password" />

            <input type="password" class="form-control" name="confirm_new_password" placeholder="Confirm New Password" />

            <input type="submit" class="btn btn-primary" name="change_settings" value="Submit" />
        </form>

        <hr />

        <p><a id="remove_account" href="<?= URL . 'user/remove'?>">Remove account</a></p>
    </div>
</div>
