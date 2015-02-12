    <div id="content">
        <form id="settings" action="" method="post">
            <h1>Settings</h1>
            <h3>General</h3>
            <label for="email">Email</label>
            <input type="text" class="form-control" name="email" placeholder="Email address" value="<?= $this->user->email ?>" />

            <label for="full_name">Full name</label>
            <input type="text" class="form-control" name="full_name" placeholder="Full name" value="<?= $this->user->full_name ?>" />

            Send reminders
            <?php if ($this->user->send_reminders): ?>
                <input type="checkbox" name="send_reminders" checked="checked" />
            <?php else: ?>
                <input type="checkbox" name="send_reminders" />
            <?php endif; ?>

            <h3>Password</h3>
            <!--<label for="current_password">Current password</label>-->
            <input type="password" class="form-control" name="current_password" placeholder="Current Password" />

            <!--<label for="new_password">New password</label>-->
            <input type="password" class="form-control" name="new_password" placeholder="New Password" />

            <!--<label for="confirm_password">Confirm new password</label>-->
            <input type="password" class="form-control" name="confirm_new_password" placeholder="Confirm Password" />

            <input type="submit" class="btn btn-primary" name="change_settings" value="Submit" />
        </form>
    </div>
</div>
