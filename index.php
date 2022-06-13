<?php
include('assets/php/config.php');
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];
// If the user is logged in redirect to the home page
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
        <title>Login</title>
        <link href="assets/css/login.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="login">
            <h1>Login</h1>
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
            <form action="assets/php/authenticate.php" method="post"> 
                <input type="text" name="user_id" placeholder="User ID or Nickname" id="user_id" required>
                <input type="password" name="password" placeholder="Password" id="password" required>
                <input type="checkbox" id="login_remember" name="login_remember"> 
                <label for="login_remember">Remember me</label>
                <p>Don’t have account ? <a href="sign_up.php">Sign up</a></p>
                <input type="submit" value="Login">
                <input type="hidden" name="_token" value="<?php echo $token ?? '' ?>" />          
            </form>
        </div>
    </body>
</html>
<?php
    unset($_SESSION["error"]);
    unset($_SESSION["successful"]);
?>