<?php
include('config.php');
session_start();
//Connect to database
include('database_connect.php');
if (!empty($_POST['_token'])) {
	if (hash_equals($_SESSION['token'], $_POST['_token'])) {
		// If the user is not logged in redirect to the login page...
		if (!isset($_SESSION['loggedin'])) {
			header('Location: ../../index.php');
			exit;
		}
		// Check if user has uploaded new image
		if (isset($_FILES['image'], $_POST['title'], $_POST['description'])) {
			// The folder where the images will be stored
			$target_dir = '../../upload_image/';
			// The path of the new uploaded image
			$rename_image_name = round(microtime(true));
			$image_path = $target_dir . $rename_image_name . basename($_FILES['image']['name']);
			// Check to make sure the image is valid
			if (!empty($_FILES['image']['tmp_name']) && getimagesize($_FILES['image']['tmp_name'])) {
				if (file_exists($image_path)) {
					$error = "Image already exists, please choose another or rename that image.";
					$_SESSION["error"] = $error;
					header("location: ../../upload_profile_image.php"); 
					exit;
				} else if ($_FILES['image']['size'] > 1000000) {
					$error = "Image file size too large, please choose an image less than 1000kb.";
					$_SESSION["error"] = $error;
					header("location: ../../upload_profile_image.php"); 
					exit;
				} else {
					if ($stmt = $con->prepare('SELECT filepath FROM images WHERE userid = ? AND profile_image = 1')) {
						$stmt->bind_param('i', $_SESSION['id']);
						$stmt->execute();
						$stmt->store_result();
						$stmt->bind_result($filepath);
						$stmt->fetch();
						if ($stmt->num_rows > 0) { //If the profile image is already exists
							// Del the old image //
							unlink("../../" . $filepath);
							// Everything checks out now we can move the uploaded image
							move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
							//Insert image info into the database (title, description, image path, and date added)
							$target_dir = 'upload_image/';
							// The path of the new uploaded image
							$image_path = $target_dir . $rename_image_name . basename($_FILES['image']['name']);
							$stmt = $con->prepare('UPDATE images SET title = ?, description = ?, filepath = ?, uploaded_date = CURRENT_TIMESTAMP WHERE userid = ? AND profile_image = 1');
							$stmt->bind_param('sssi', $_POST['title'], $_POST['description'], $image_path, $_SESSION['id']);
							$stmt->execute();
							$successful = "Profile image uploaded successfully.";
							$_SESSION["successful"] = $successful;
							header("location: ../../upload_profile_image.php"); 
						}else{
							// Everything checks out now we can move the uploaded image
							move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
							//Insert image info into the database (title, description, image path, and date added)
							$target_dir = 'upload_image/';
							// The path of the new uploaded image
							$image_path = $target_dir . $rename_image_name . basename($_FILES['image']['name']);
							$stmt = $con->prepare('INSERT INTO images (userid, title, description, filepath, uploaded_date, profile_image) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, 1)');
							$stmt->bind_param('isss', $_SESSION['id'], $_POST['title'], $_POST['description'], $image_path);
							$stmt->execute();
							$successful = "Profile image uploaded successfully.";
							$_SESSION["successful"] = $successful;
							header("location: ../../upload_profile_image.php"); 
						}
					}
				}
			} else {
				$error = "Please upload an image.";
				$_SESSION["error"] = $error;
				header("location: ../../upload_profile_image.php"); 
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