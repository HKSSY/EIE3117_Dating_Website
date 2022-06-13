<?php
include('config.php');
session_start();
//Connect to database
include('database_connect.php');
if (!empty($_POST['_token'])) {
	if (hash_equals($_SESSION['token'], $_POST['_token'])) {
		// Now we check if the data was submitted, isset() function will check if the data exists.
		if (!isset($_POST['self_description'])) {
			// Could not get the data that should have been sent.
			$error = "Please fill in all fields.";
			$_SESSION["error"] = $error;
			header("location: ../../update_description.php");
			exit;
		}

		if ($stmt = $con->prepare('SELECT self_description FROM accounts WHERE id = ?')) {
				// Bind parameters (s = string, i = int, b = blob, etc), in our case the user id is a string so we use "s"
				$stmt->bind_param('i', $_SESSION['id']);
				$stmt->execute();
				// Store the result so we can check if the account exists in the database.
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($self_description);
					$stmt->fetch();
					if ($stmt = $con->prepare('UPDATE accounts SET self_description=? WHERE id=?')) {
						//$self_description_filter = filter_var($_POST['self_description'], FILTER_SANITIZE_STRING);
						$stmt->bind_param('si', $_POST['self_description'], $_SESSION['id']);
						$stmt->execute();
						$successful = "Description updated successfully.";
						$_SESSION["successful"] = $successful;
						header("location: ../../update_description.php"); 
					} else {
						$error = "Please try again later.";
						$_SESSION["error"] = $error;
						header("location: ../../update_description.php"); 
					}
				}
		}
	}else{
		// Log this as a warning and keep an eye on these attempts
		echo "Your connection is not secure";
		exit;
	}
}

$con->close();
?>