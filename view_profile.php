<?php
include('assets/php/config.php');
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
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
			<h2>Profile Page</h2>
			<div>
				<?php
					if ($stmt->num_rows < 1) {
						echo"<p>User not found</p></div></div></body></html>";
						exit;
					} else {
						$stmt->bind_result($nickname, $email, $dob, $gender, $self_description);
						$stmt->fetch();
						$stmt->close();
						$dob_to_age = $dob;
						$today = date("Y-m-d");
						$diff = date_diff(date_create($dob_to_age), date_create($today));
						$age = $diff->format('%y');
						$stmt = $con->prepare('SELECT filepath FROM images WHERE userid = ? AND profile_image = 1');
						$stmt->bind_param('i', $_GET['id']);
						$stmt->execute();
						$stmt->store_result();
						if ($stmt->num_rows < 1) {
							$filepath = 'assets/img/default_profile_image.png';
							$stmt->close();
						}else{
							$stmt->bind_result($filepath);
							$stmt->fetch();
							$stmt->close();
						}
					}
				?>
				<p>Account details are below:</p>
				<table>
				<tr>
					<td>User image:</td>
						<td><img src="<?=htmlspecialchars($filepath, ENT_QUOTES, 'UTF-8')?>" width=auto height="200"></td>
					</tr>
					<tr>
					<td>User id:</td>
						<td><?=$_GET['id']?></td>
					</tr>
					<tr>
						<td>Nickname:</td>
						<td><?=htmlspecialchars($nickname, ENT_QUOTES, 'UTF-8')?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=htmlspecialchars($email, ENT_QUOTES, 'UTF-8')?></td>
					</tr>
					<tr>
						<td>Date of born:</td>
						<td><?=htmlspecialchars($dob, ENT_QUOTES, 'UTF-8')?></td>
					</tr>
					<tr>
						<td>Age:</td>
						<td><?=htmlspecialchars($age, ENT_QUOTES, 'UTF-8')?></td>
					</tr>
					<tr>
						<td>Gender:</td>
						<td><?=htmlspecialchars($gender, ENT_QUOTES, 'UTF-8')?></td>
					</tr>
					<tr>
						<td>Self-description:</td>
						<td><?=htmlspecialchars($self_description, ENT_QUOTES, 'UTF-8')?></td>
					</tr>
				</table>
			</div>
			<div>
				<p>Private message:</p>
				<input type="submit" value="Send message" onclick="window.location.href='message.php?id=<?=$_GET['id']?>';">
			</div>
		</div>
	</body>
</html>