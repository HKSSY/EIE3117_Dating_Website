<?php
include('assets/php/config.php');
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Security-Policy" content="default-src *; script-src 'self' 'unsafe-inline' 'unsafe-eval'; img-src 'self'">
		<title>Home Page</title>
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
			<div>
				<h2>Home Page</h2>
				<p>Welcome back, <?=htmlspecialchars($_SESSION['nickname'], ENT_QUOTES, 'UTF-8')?>!</p>
			</div>
			<div>
				<p>Shortcut:</p>
				<input type="submit" value="Your profile" onclick="window.location.href='profile.php';">
				<input type="submit" value="View user" onclick="window.location.href='user_list.php';">
				<input type="submit" value="Read my message" onclick="window.location.href='message.php?id=<?=$_SESSION['id']?>';">
			</div>
		</div>
	</body>
</html>