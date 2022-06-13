<?php
// Decrypt cookie
function decryptCookie( $ciphertext ) {

   $cipher = "aes-256-cbc";

   list($encrypted_data, $iv,$key) = explode('::', base64_decode($ciphertext));
   return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);

}
$userid = decryptCookie($_COOKIE['rememberme_user_id']);
$password = decryptCookie($_COOKIE['rememberme_user_password']);
include('config.php');
session_start();
//Connect to database
include('database_connect.php');
$error = "Incorrect user id or password.";
if ($stmt = $con->prepare('SELECT nickname, password FROM accounts WHERE id = ?')) { //Use id login
   // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
   $stmt->bind_param('s', $userid);
   $stmt->execute();
   // Store the result so we can check if the account exists in the database.
   $stmt->store_result();
   if ($stmt->num_rows > 0) {
      $stmt->bind_result($nickname, $password_hash);
      $stmt->fetch();
      // Account exists, now we verify the password.
		// Note: remember to use password_hash in your registration file to store the hashed passwords.
      if (password_verify($password, $password_hash)) {
         // Verification success! User has logged-in!
			// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
         session_regenerate_id();
         $_SESSION['loggedin'] = TRUE;
			$_SESSION['id'] = $userid;
			$_SESSION['nickname'] = $nickname;
         header('Location: ../../home.php');
      } else {
         // Incorrect password
         $_SESSION["error"] = $error;
         header("location: ../../index.php"); 
      }
   } elseif ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE nickname = ?')) { //Use nickname login
      // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
      $stmt->bind_param('s', $userid);
      $stmt->execute();
      $stmt->store_result();
      if ($stmt->num_rows > 0) {
         $stmt->bind_result($id, $password_hash);
         $stmt->fetch();
         if (password_verify($password, $password_hash)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['nickname'] = $userid;
            $_SESSION['id'] = $id;
            header('Location: ../../home.php');
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
?>
