<?php
	session_start();
	require_once('new-connection.php');

	function all_messages()
	{
	  $insert_user_query = "SELECT messages.id, messages.user_id, messages.message, messages.created_at, users.first_name, users.last_name 
	            FROM messages 
	            LEFT JOIN users ON users.id = messages.user_id";
	  $insert_user_result = fetch_all($insert_user_query);
	  return $insert_user_result;
	}

	$messages = fetch_all($insert_user_query);
?>

<html>
<head>
	<title>The WALL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="container">
		<div id="header">
			<h3>CodingDojo Wall</h3>
			<p>Welcome <?= $_SESSION['current_user'][0]['first_name']; ?></p>
			<a href="process.php" data-method="POST">LOG OFF</a>
		</div>

		<div id="line"></div>
<?php
		if (isset($_SESSION['error'])) 
		{
			echo "<p>" . $_SESSION['error'] . "</p>";
			unset($_SESSION['error']);
		}
?>
		<div id="content">
			<!-- messages/posts -->
			<h2 class="message">Post a Message: </h2>
			<form action="process.php" method="POST">
				<input type="hidden" name="action" value="post_message">
				<textarea name="message"></textarea>
				<input type="submit" value="Write Something">
			</form>
<?php
			foreach ($messages as $message) 
			{
				$comments = all_comments($message['id']);
				$date = date("F jS, Y", strtotime($message['created_at']));
				echo "<p class='message'>" . $message['first_name'] . " " . $message['last_name'] . " " . "-" . " " . $date . "</p>";
				echo "<p class='message'>" . $message['message'] . "</p>";
				echo "<h4 class='comment'>Post a comment</h4>";
?>
			<!-- comments on posts/messages -->
			<form action="process.php" method="POST">
				<input type="hidden" name="action" value="__">
				<textarea name="comment"></textarea>
				<input type="submit" value="Comment">
			</form>
<?php
				if (isset($comments)) 
				{
					foreach ($comments as $comment) 
					{
						$date = date("F jS, Y", strtotime($comment['created_at']));
						echo "<p class='comment'>" . $comment['first_name'] . " " . $comment['last_name'] . " " . "-" . " " . $date . "</p>";
						echo "<p class='comment'>" . $comment['comment'] . "</p>";
					}
				}
				echo "<hr>";
			}
?>
		</div>
	</div>
</body>
</html>