<?php
include('assets/php/config.php');
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];
if (isset($_SESSION['loggedin'])) {
	header('location: home.php');
	exit;
} elseif (isset($_COOKIE['rememberme_user_id'], $_COOKIE['rememberme_user_password'])) {
    header("location: assets/php/decrypt_cookie_auth.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Security-Policy" content="default-src *; script-src 'self' 'unsafe-inline' 'unsafe-eval'; img-src 'self'">
		<title>Sign up</title>
        <link href="assets/css/sign_up.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head>
    <body>
        <div class="register">
            <h1>Sign Up</h1>
            <?php
                if(isset($_SESSION["error"])){
                    $error = $_SESSION["error"];
                    echo "<p class='error'>";
                    echo "<span>$error</span>";
                    echo "<p>";
                }
            ?>
            <?php
                if(isset($_SESSION["successful"])){
                    $successful = $_SESSION["successful"];
                    echo "<p class='successful'>";
                    echo "<span>$successful</span>";
                    echo "<p>";
                }
            ?>
            <form action="assets/php/sign_up.php" method="post" autocomplete="off">
                <label for="username">
                    <i class="fas fa-user"></i>
                </label>
                <input type="text" name="username" placeholder="Nick name" id="username" required>
                <label for="password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" name="password" placeholder="Password" id="password" required>
                <label for="confirm_Password">
                    <i class="fas fa-lock"></i>
                </label>
                <input type="password" name="confirm_Password" placeholder="Confirm Password" id="confirm_Password" required>
                <label for="email">
                    <i class="fas fa-envelope"></i>
                </label>
                <input type="email" name="email" placeholder="Email" id="email" required>
                <label for="dob">
                    <i class="fas fa-birthday-cake"></i>
                </label>
                <input type="date" name="dob" placeholder="Day of birth" id="dob" required min="1910-01-01" max="2000-12-31">
                <label for="gender">
                    <i class="fas fa-venus-mars"></i>
                </label>
                <select id="gender" name="gender">
                    <option value="none" selected disabled hidden>Select an Option</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <textarea id="self_description" name="self_description" placeholder="Enter your self-description (up to 700)" maxlength ="700" rows="4" cols="50" required></textarea>
                <input type="submit" value="Register">
                <input type="hidden" name="_token" value="<?php echo $token ?? '' ?>" />
            </form>
        </div>
        <script src="assets/js/max_date_limit.js"></script>
    </body>
</html>
<?php
    unset($_SESSION["error"]);
    unset($_SESSION["successful"]);
?>