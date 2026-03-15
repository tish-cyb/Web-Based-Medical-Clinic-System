<form action="process_login.php" method="POST">
    <div class="mb-3">
        <label class="form-label">Email / ID / License</label>
        <!--
            CHANGED: name="identifier"  →  name="identifier"  (kept)
            process_login.php reads $_POST['identifier'] — must match this name.
            Do NOT rename to "username" unless you also change process_login.php.
        -->
        <input type="text" name="identifier" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <!--
        ADDED: name="login_btn"
        process_login.php checks isset($_POST['login_btn']) before doing anything.
        Without this the form submission is silently ignored.
    -->
    <button type="submit" name="login_btn" class="btn btn-login w-100">
        Sign In
    </button>
</form>