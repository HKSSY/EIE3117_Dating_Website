<?php
include('assets/php/config.php');
session_start();
// If the user is not logged in redirect to the login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];
//Connect to database
include('assets/php/database_connect.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="Content-Security-Policy" content="default-src *; script-src 'self' 'unsafe-inline' 'unsafe-eval'; img-src 'self'">
		<title>Change password</title>
		<link href="assets/css/home.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
        <nav class="navtop">
			<div>
                <h1><a href="home.php">Dating Website</a></h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="assets/php/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
        <div class="content">
            <h2>Update self-description</h2>
                <div>
                    <p>Please fill in all fields:</p>
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
                    <form action="assets/php/upload_description.php" method="post" autocomplete="off">
                        <textarea id="self_description" name="self_description" placeholder="Enter your self-description (up to 700)" maxlength ="700" rows="4" cols="999" required></textarea>
                        <input type="submit" value="Confirm">
                        <input type="hidden" name="_token" value="<?php echo "$token" ?? '' ?>" />
                    </form>
                </div>
		</div>
	</body>
</html>
<?php
    unset($_SESSION["error"]);
    unset($_SESSION["successful"]);
?>
