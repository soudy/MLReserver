    <div id="content">
        <form id="remove" action="" method="post">
            <h1>Remove user</h1>
            <p>Are you sure you want to remove user "<?= $this->user->username; ?>"?
            This cannot be undone.</p>
            <input type="submit" name="remove_user" class="btn btn-danger" value="Remove user" />
            <input type="button" onclick="history.go(-1);" class="btn btn-normal" value="Go back" />
        </form>
    </div>
</div>
