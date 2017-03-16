<!doctype html>
<?php
session_start();
if(!isset($_SESSION['logged']))
	header("Location: login.php");
?>
<html>
	<head>
		<title>Thanks!</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="initial-scale=1.0">
	</head>

	<body>
		<h1>Hello <?php echo $_SESSION['logged']; ?>!</h1>
		<a href="logout.php">Log out!</a>
	</body>
</html>