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
$result = mysqli_query($con,"SELECT id, nickname, dob, gender FROM accounts");  
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta http-equiv="Content-Security-Policy" content="default-src *; script-src 'self' 'unsafe-inline' 'unsafe-eval'; img-src 'self'">
		<title>User list</title>
		<link href="assets/css/user_list.css" rel="stylesheet" type="text/css">
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
            <h2>User list</h2>
            <div>
                <?php
                    if ($result->num_rows > 0) { //If we can find users on the database
                        $button_build_1 = '<input type="submit" value="View details" onclick="window.location.href=';
                        $button_build_2 = ';">';
                        echo "<table><tr><th>User id</th><th>Nickname</th><th>Date of birth</th><th>Gender</th><th>View profile</th></tr>";
                        while ($row = mysqli_fetch_array($result)) {
                            if ($row[0] == $_SESSION['id']){
                                echo "<tr><td>".$row[0]." (Current user)</td>";
                            } else {
                                echo "<tr><td>".$row[0]."</td>";
                            }
                            echo "<td>".htmlspecialchars($row[1])."</td>";
                            echo "<td>".$row[2]."</td>";
                            echo "<td>".$row[3]."</td>";
                            if ($row[0] == $_SESSION['id']){
                                echo "<td>".$button_build_1."'profile.php"."'".$button_build_2."</td></tr>";
                            } else {
                                echo "<td>".$button_build_1."'view_profile.php?id=".$row[0]."'".$button_build_2."</td></tr>";
                            }
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No users found</p>";
                    }
                ?>
            </div>
        </div>
    </body>
</html>
