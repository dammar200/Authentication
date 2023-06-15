<?php session_start(); 
if(isset($_SESSION['user'])){
    header("Location:home.php");
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <title>Title</title>
</head>

<body onload="unsetSession()">
    <div class="container w-50 mx-auto my-5 py-5">
        <h2>Register</h2>
        <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-<?= $_SESSION['color'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif ?>
        <form action="process/register.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" <?php if (isset($_SESSION['username'])) : ?> value="<?= $_SESSION['username'] ?>" <?php endif ?>>
                <?php if (isset($_SESSION['userError'])) : ?>
                    <small class="text-danger"><?= $_SESSION['userError'] ?></small>
                <?php endif ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" <?php if (isset($_SESSION['password'])) : ?> value="<?= $_SESSION['password'] ?>" <?php endif ?>>
                <?php if (isset($_SESSION['passwordError'])) : ?>
                    <small class="text-<?= $_SESSION['color'] ?>"><?= $_SESSION['passwordError'] ?></small>
                <?php endif ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Captcha</label>
                <input type="number" class="form-control" name="captcha" <?php if (isset($_SESSION['captcha'])) : ?> value="<?= $_SESSION['captcha'] ?>" <?php endif ?>>
                <?php if (isset($_SESSION['captcha_error'])) : ?>
                    <small class="text-danger"><?= $_SESSION['captcha_error'] ?></small>
                <?php endif ?>
                <img src="process/captcha.php" /><br>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
        <div class="text-center">
            <span>Already Registered ? </span><a href="login.php">Login</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

    <script>
        function unsetSession() {
            <?php
            unset($_SESSION['passwordError']);
            unset($_SESSION['captcha_error']);
            unset($_SESSION['userError']);
            unset($_SESSION['username']);
            unset($_SESSION['password']);
            unset($_SESSION['captcha']);
            unset($_SESSION['message']);
            ?>
        }
    </script>
</body>

</html>