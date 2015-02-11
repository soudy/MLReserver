<form id="log-in" method="post" class="input-group">
    <h1>MLReserver</h1>
    <input type="text" name="username" id="u_username" class="form-control" 
    value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>" placeholder="Username" required />
    <input type="password" name="password" id="u_password" class="form-control" placeholder="Password" required />
    <!--<input type="checkbox" name="stay_logged_in" id="stay_logged_in" />
    <label for="stay_logged_in">Stay logged in</label>-->
    <input type="submit" class="btn btn-primary" name="login" value="Log in" />
    <a href="#">
        <p>Forgot password?</p>
    </a>
</form>
