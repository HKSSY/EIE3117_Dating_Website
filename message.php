<?php
include('assets/php/config.php');
session_start();
// If the user is not logged in redirect to the login page
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}
//Connect to database
include('assets/php/database_connect.php');
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT nickname, email, dob, gender, self_description FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$stmt->store_result(); 
/*
if ($_SESSION['id'] != $_GET['id']){
	header("location: message.php?id=".$_SESSION['id']); 
	exit;
} 
*/
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="Content-Security-Policy" content="default-src *; script-src 'self' 'unsafe-inline' 'unsafe-eval'; img-src 'self'">
		<title>Profile Page</title>
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
			<h2>Message Page</h2>
			<div>
			<div class="comments"></div>
				<script>
				const comments_page_id = <?=htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8')?>; // This number should be unique on every page
				fetch("assets/php/pm_function.php?page_id=" + comments_page_id).then(response => response.text()).then(data => {
					document.querySelector(".comments").innerHTML = data;
					document.querySelectorAll(".comments .write_comment_btn, .comments .reply_comment_btn").forEach(element => {
						element.onclick = event => {
							event.preventDefault();
							document.querySelectorAll(".comments .write_comment").forEach(element => element.style.display = 'none');
							document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "']").style.display = 'block';
							document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "'] input[name='name']").focus();
						};
					});
					document.querySelectorAll(".comments .write_comment form").forEach(element => {
						element.onsubmit = event => {
							event.preventDefault();
							fetch("assets/php/pm_function.php?page_id=" + comments_page_id, {
								method: 'POST',
								body: new FormData(element)
							}).then(response => response.text()).then(data => {
								element.parentElement.innerHTML = data;
							});
						};
					});
				});
				</script>
		</div>
	</body>
</html>
