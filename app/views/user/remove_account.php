    <div id="content">
        <form id="remove" action="" method="post">
            <h1>Remove account</h1>
            <div class="alert alert-danger">
                A removed account cannot be recovered.
            </div>
            <p>If you're sure about removing your account, enter you password and
            submit</p>

            <input type="password" class="form-control" name="password" placeholder="Password" required />
            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required />

            <input type="submit" name="remove_account" class="btn btn-danger" value="Remove account" />
        </form>
    </div>
</div>
