<?php
include('config.php');
session_start();
//Connect to database
include('database_connect.php');
if (!empty($_POST['_token'])) {
	if (hash_equals($_SESSION['token'], $_POST['_token'])) {
		// Now we check if the data was submitted, isset() function will check if the data exists.
		if (!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['dob'], $_POST['gender'], $_POST['self_description'])) {
			// Could not get the data that should have been sent.
			$error = "Please fill in all fields.";
			$_SESSION["error"] = $error;
			header("location: ../../sign_up.php"); 
			exit;
		}
		// Make sure the submitted registration values are not empty.
		if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['dob']) || empty($_POST['gender']) || empty($_POST['self_description'])) {
			// One or more values are empty.
			$error = "Please fill in all fields.";
			$_SESSION["error"] = $error;
			header("location: ../../sign_up.php"); 
			exit;
		}
		if (($_POST['password']) != ($_POST['confirm_Password'])) {
			// Pasword not match.
			$error = "Pasword not match.";
			$_SESSION["error"] = $error;
			header("location: ../../sign_up.php"); 
			exit;
		}
		// We need to check if the account with that username exists.
		if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE nickname = ?')) {
			// Validate password strength
			$uppercase = preg_match('@[A-Z]@', $_POST['password']);
			$lowercase = preg_match('@[a-z]@', $_POST['password']);
			$number    = preg_match('@[0-9]@', $_POST['password']);
			$specialChars = preg_match('@[^\w]@', $_POST['password']);
			if (!$uppercase || !$lowercase || !$number || strlen($_POST['password']) < 8) {
				$error = "Password should be at least 8 characters in length and should include at least one upper and lower case letter, and one number.";
				$_SESSION["error"] = $error;
				header("location: ../../sign_up.php"); 
			} else {
				// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
				$stmt->bind_param('s', $_POST['username']);
				$stmt->execute();
				$stmt->store_result();
				// Store the result so we can check if the account exists in the database.
				if ($stmt->num_rows > 0) {
					// Username already exists
					$error = "Nickname already exists. Please try another one.";
					$_SESSION["error"] = $error;
					header("location: ../../sign_up.php"); 
				} else {
					// Username doesnt exists, insert new account
					if ($stmt = $con->prepare('INSERT INTO accounts (nickname, password, email, dob, gender, self_description) VALUES (?, ?, ?, ?, ?, ?)')) {
						// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
						$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
						//$self_description_filter = filter_var($_POST['self_description'], FILTER_SANITIZE_STRING);
						$stmt->bind_param('ssssss', $_POST['username'], $password, $_POST['email'], $_POST['dob'], $_POST['gender'], $_POST['self_description']);
						$stmt->execute();
						$successful = "Registered successfully, please login.";
						$_SESSION["successful"] = $successful;
						header("location: ../../index.php"); 
					} else {
						// Something is wrong with the sql statement, check to make sure accounts table exists with all fields.
						$error = "Temporarily cannot register an account, please try again later.";
						$_SESSION["error"] = $error;
						header("location: ../../sign_up.php"); 
					}
				}
				$stmt->close();
			}
		} else {
			// Something is wrong with the sql statement, check to make sure accounts table exists with all fields.
			$error = "Temporarily cannot register an account, please try again later.";
			$_SESSION["error"] = $error;
			header("location: ../../sign_up.php"); 
		}
	}else{
		echo "Not a security connection";
		exit;
	}
}

$con->close();
?>