<?php
include('config.php');
session_start();
//Connect to database
include('database_connect.php');
if (!empty($_POST['_token'])) {
	if (hash_equals($_SESSION['token'], $_POST['_token'])) {
		// Now we check if the data was submitted, isset() function will check if the data exists.
		if (!isset($_POST['current_password'], $_POST['new_Password'], $_POST['confirm_Password'])) {
			// Could not get the data that should have been sent.
			$error = "Please fill in all fields.";
			$_SESSION["error"] = $error;
			header("location: ../../change_password.php");
			exit;
		}
		// Make sure the submitted registration values are not empty.
		if (empty($_POST['current_password']) || empty($_POST['new_Password']) || empty($_POST['confirm_Password'])) {
			// One or more values are empty.
			$error = "Please fill in all fields.";
			$_SESSION["error"] = $error;
			header("location: ../../change_password.php"); 
			exit;
		}
		if ($stmt = $con->prepare('SELECT nickname, password FROM accounts WHERE id = ?')) {
			// Validate password strength
			$uppercase = preg_match('@[A-Z]@', $_POST['new_Password']);
			$lowercase = preg_match('@[a-z]@', $_POST['new_Password']);
			$number    = preg_match('@[0-9]@', $_POST['new_Password']);
			$specialChars = preg_match('@[^\w]@', $_POST['new_Password']);
			if (($_POST['new_Password']) != ($_POST['confirm_Password'])) {
				// Pasword not match.
				$error = "Pasword not match.";
				$_SESSION["error"] = $error;
				header("location: ../../change_password.php"); 
			} elseif (!$uppercase || !$lowercase || !$number || strlen($_POST['new_Password']) < 8) {
				$error = "Password should be at least 8 characters in length and should include at least one upper and lower case letter, and one number.";
				$_SESSION["error"] = $error;
				header("location: ../../change_password.php");
			} else {
				// Bind parameters (s = string, i = int, b = blob, etc), in our case the user id is a string so we use "s"
				$stmt->bind_param('i', $_SESSION['id']);
				$stmt->execute();
				// Store the result so we can check if the account exists in the database.
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($nickname, $password);
					$stmt->fetch();
					// Account exists, now we verify the password.
					// Note: remember to use password_hash in your registration file to store the hashed passwords.
					if (password_verify($_POST['current_password'], $password)) {
						// Verification success!
						if ($stmt = $con->prepare('UPDATE accounts SET password=? WHERE id=?')) {
							$password = password_hash($_POST['confirm_Password'], PASSWORD_DEFAULT);
							$stmt->bind_param('si', $password, $_SESSION['id']);
							$stmt->execute();
							$successful = "Password changed successfully.";
							$_SESSION["successful"] = $successful;
							header("location: ../../change_password.php"); 
						}
					} else {
						$error = "Current pasword not match, please try again.";
						$_SESSION["error"] = $error;
						header("location: ../../change_password.php"); 
					}
				}
			}
		}
	}else{
		echo "Your connection is not secure";
		exit;
	}
}

$con->close();
?>