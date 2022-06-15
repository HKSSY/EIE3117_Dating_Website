<?php
include('assets/php/config.php');
session_start();
// If the user is not logged in redirect to the login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
// Using token to improve the website security
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
		<title>Change profile image</title>
		<link href="assets/css/upload_profile.css" rel="stylesheet" type="text/css">
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
            <h2>Change profile image</h2>
            <div>
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
                <form action="assets/php/upload_profile_image_checker.php" method="post" enctype="multipart/form-data">
                    <label for="image">Choose Image</label>
                    <input type="file" name="image" accept="image/*" id="image">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" maxlength="30">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" maxlength="40">
                    <input type="submit" value="Upload Image" name="submit">
                    <input type="hidden" name="_token" value="<?php echo $token ?? '' ?>" /> 
                </form>
            </div>
        </div>
    </body>
</html>
<?php
    unset($_SESSION["error"]);
    unset($_SESSION["successful"]);
?>
