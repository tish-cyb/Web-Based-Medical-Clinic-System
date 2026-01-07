<form action="process_login.php" method="POST">
    <div class="mb-3">
        <label class="form-label">Email / ID / License</label>
        <input type="text" name="identifier" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-login w-100">
        Sign In
    </button>
</form>
