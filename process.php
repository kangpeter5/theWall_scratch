<?php
	session_start();
	require_once('new-connection.php');

	// check to see if user is registered, logged in, or logging out
	if (isset($_POST['action']) && $_POST['action'] == "register") 
	{
		register_user();
	}
	if (isset($_POST['action']) && $_POST['action'] == "login") 
	{
		login_user();
	}
	if (isset($_POST['action']) && $_POST['action'] == "logout") 
	{
		logout();
	}

	// function to register users
	function register_user()
	{
		// sanitize inputs
		$first_name = escape_this_string($_POST['first_name']);
		$last_name = escape_this_string($_POST['last_name']);
		$email = escape_this_string($_POST['email']);
		$password = escape_this_string($_POST['password']);
		$confirm_password = escape_this_string($_POST['confirm_password']);

		// validate all inquiries... aka all the inputs from the forms
		$_SESSION['errors'] = array();

		if (empty($first_name)) 
		{
			$_SESSION['errors']['first_name'] = "First name can't be blank!";
		}
		if (empty($last_name)) 
		{
			$_SESSION['errors']['last_name'] = "Last name can't be blank!";
		}
		if (empty(filter_var($email, FILTER_VALIDATE_EMAIL))) 
		{
			$_SESSION['errors']['email'] = "That's not a valid email.";
		}
		if (empty($password)) 
		{
			$_SESSION['errors']['password'] = "Password can't be blank!";
		}
		if ($confirm_password !== $password) 
		{
			$_SESSION['errors'] = "Passwords aren't the same!";
		}
		//End Validation Checks

		if (count($_SESSION['errors']) == 0) 
		{
			$password = md5($_POST['password']);
			$insert_user_query= "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('{$first_name}', '{$last_name}', '{$email}', '{$password}', NOW(), NOW())";
			$insert_user_result= run_mysql_query($insert_user_query);

			$_SESSION['success'] = "User has been added";
		}

		header("Location: index.php");
	}

	// function for logging users into the wall
	function login_user()
	{
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
		{
			$_SESSION['errors'][] = "Email is not valid format";
		}
		$password = md5($_POST['password']);
		$insert_user_query = "SELECT * FROM users WHERE email = '{$_POST['email']}' AND password = '{$password}'";
		$insert_user_result = fetch_record($insert_user_query);

		if ($insert_user_result== NULL || count($insert_user_result)== FALSE) 
		{
			$_SESSION['errors'][] = "Cannot find a user with that info";
			header("location: index.php");
		}
		else
		{
			$_SESSION['current_user'] = $insert_user_result;
			header("location: thewall.php");
		}
	}

	// function to add posts or messages
	if (isset($_POST['action']) && $_POST['action'] == "post_message") 
	{
		add_message($_POST);
	}
	function add_message($post)
	{
		global $connection;

		if (empty($post['message'])) 
		{
			$_SESSION['error'] = "Message can't be blank";
			header("location: thewall.php");
		}
		else
		{
			$message = $connection->real_escape_string($post['message']);
			$user_id = intval($_SESSION['current_user'][0]['id']);
			$insert_user_query = "INSERT INTO messages (message, created_at, updated_at, user_id) VALUES ('{$message}', NOW(), NOW(), {$user_id})";
			$insert_user_result = run_mysql_query($insert_user_query);
		}

		header("location: thewall.php");
	}

	// function to add_comment()
	if (isset($_POST['action']) && $_POST['action'] == "post_comment") 
	{
			add_comment($_POST);
	}

	function add_comment($post)
	{
		global $connection;

		if (empty($post['comment'])) 
		{
			$_SESSION['error'] = "Comment can't be blank";
			header("location: thewall.php");
		}
		else
		{
			$comment = $connection->real_escape_string($post['comment']);
			$user_id = intval($_SESSION['current_user'][0]['id']);
			$message_id = intval($post['message_id']);
			$insert_user_query = "INSERT INTO comments (comment, created_at, updated_at, message_id, user_id) 
					VALUES ('{$comment}', NOW(), NOW(), {$message_id}, {$user_id})";
			$insert_user_result = run_mysql_query($insert_user_query);
		}

		header("location: thewall.php");
	}

	// function for logging users out
	function logout()
	{
		session_destroy();
		header("Location: index.php");
	}

	//exit after using header() function above
	die();
?>