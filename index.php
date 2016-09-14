<?php
	session_start();

	if (isset($_SESSION['user']) && $_SESSION['user']['logged in']= TRUE) 
	{
		header("Location: thewall.php");
	}
?>

<html>
<head>
	<title>Index of the WALL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="container">
		<h1>WELCOME TO THE WALL</h1>

<?php
		if (isset($_SESSION['errors'])) 
		{
			foreach ($_SESSION['errors'] as $error) 
			{
				echo "<p class='error'>" . $error . " </p>";
			}
			unset($_SESSION['errors']);
		}
?>

		<h3>Register:</h3>
<?php
		if (isset($_SESSION['success'])) 
		{
			echo "<p class='success'>" . $_SESSION['success'] . "</p>";
			unset($_SESSION['success']);
		}
?>
		<form action="process.php" method="post">
			<input type="hidden" name="action" value="register">
			First Name: <input type="text" name="first_name" ></br>
			Last Name: <input type="text" name="last_name" ></br>
			Email: <input type="text" name="email" ></br>
			Password: <input type="password" name="password" ></br>
			Confirm Password: <input type="password" name="confirm_password" ></br>
			<input type="submit" value="register" ></br>
		</form>

		<h3>Login: </h3>
		<form action="process.php" method="post">
			<input type="hidden" name="action" value="login">
			Email: <input type="text" name="email" ></br>
			Password: <input type="password" name="password" ></br>
			<input type="submit" value="login" ></br>
		</form>
	</div>
</body>
</html>