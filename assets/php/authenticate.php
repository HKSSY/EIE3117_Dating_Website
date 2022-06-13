<?php
include('config.php');
session_start();
//Connect to database
include('database_connect.php');
include 'encrypt_cookie.php';
$error = "Incorrect user id or password.";
if (!empty($_POST['_token'])) {
	if (hash_equals($_SESSION['token'], $_POST['_token'])) {
		// Now we check if the data from the login form was submitted, isset() will check if the data exists.
		if ( !isset($_POST['user_id'], $_POST['password']) ) {
			// Could not get the data that should have been sent.
			$_SESSION["error"] = 'Please fill both the user id and password fields!';
			header("location: ../../index.php");
			exit;
		}
		// Proceed to process the form data
		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $con->prepare('SELECT nickname, password FROM accounts WHERE id = ?')) { //Use id login
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the user id is a string so we use "s"
			$stmt->bind_param('i', $_POST['user_id']);
			$stmt->execute();
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
			if ($stmt->num_rows > 0) {
				$stmt->bind_result($nickname, $password);
				$stmt->fetch();
				// Account exists, now we verify the password.
				// Note: remember to use password_hash in your registration file to store the hashed passwords.
				if (password_verify($_POST['password'], $password)) {
					// Verification success! User has logged-in!
					// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
					session_regenerate_id();
					$_SESSION['loggedin'] = TRUE;
					$_SESSION['id'] = $_POST['user_id'];
					$_SESSION['nickname'] = $nickname;
					if(!empty($_POST["login_remember"])) {
						$days = 7;
						$value = encryptCookie($_POST['user_id']);
						$value2 = encryptCookie($_POST['password']);
						setcookie ("rememberme_user_id",$value,['samesite' => 'None','secure' => true,'httponly' => true],time()+ ($days * 24 * 60 * 60 * 1000), '/');
						setcookie ("rememberme_user_password",$value2,['samesite' => 'None','secure' => true,'httponly' => true],time()+ ($days * 24 * 60 * 60 * 1000), '/');
						header('Location: ../../home.php');
					} elseif (empty($_POST["login_remember"])) {
						header('Location: ../../home.php');
					}
				} else {
					// Incorrect password
					$_SESSION["error"] = $error;
					header("location: ../../index.php"); 
				}
			} elseif ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE nickname = ?')) { //Use nickname login
				// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
				$stmt->bind_param('s', $_POST['user_id']);
				$stmt->execute();
				// Store the result so we can check if the account exists in the database.
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					$stmt->bind_result($id, $password);
					$stmt->fetch();
					// Account exists, now we verify the password.
					// Note: remember to use password_hash in your registration file to store the hashed passwords.
					if (password_verify($_POST['password'], $password)) {
						// Verification success! User has logged-in!
						// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
						session_regenerate_id();
						$_SESSION['loggedin'] = TRUE;
						$_SESSION['nickname'] = $_POST['user_id'];
						$_SESSION['id'] = $id;
						if(!empty($_POST["login_remember"])) {
							$days = 7;
							$value = encryptCookie($_POST['user_id']);
							$value2 = encryptCookie($_POST['password']);
							setcookie ("rememberme_user_id",$value,time()+ ($days * 24 * 60 * 60 * 1000), '/');
							setcookie ("rememberme_user_password",$value2,time()+ ($days * 24 * 60 * 60 * 1000), '/');
							header('Location: ../../home.php');
						} elseif (empty($_POST["login_remember"])) {
							header('Location: ../../home.php');
						}
					} else {
						// Incorrect password
						$_SESSION["error"] = $error;
						header("location: ../../index.php"); 
					}
				} else {
					// Incorrect username
					$_SESSION["error"] = $error;
					header("location: ../../index.php");
				}
			}
			$stmt->close();
		}
	} else {
		// Log this as a warning and keep an eye on these attempts
		echo "Your connection is not secure";
		exit;
	}
}

?>