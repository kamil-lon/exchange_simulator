<!DOCTYPE HTML>

<?php

require_once("dbconfig.php");

if(isset($_SESSION['logged']))
{
	header("Location: index.php");	
}

if(isset($_POST['submited']))
{
	$user_name = $_POST['login'];
	$user_email = $_POST['login'];
	$user_pass = $_POST['password'];
	
	if($user->login($user_name,$user_email,$user_pass))
	{
		header("Location: index.php");
	}
	else
	{
		if(isset($_SESSION['none']))
		{
			$error_n = '<span style="color: red; font-size: 15px;">User with this nickname or email doesn\'t exists!</span>';
			unset($_SESSION['none']);
		}
		else
		{
			$error_p = '<span style="color: red; font-size: 15px;">Incorrect password!</span><br>';
		}
	}
}
?>

<html>
	<head>
		<title>Login Page</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>

<body>
	<div class="main">
		<form method="post">
			username or e-mail<br>
			<input type="text" name="login" class="place" placeholder="log@in.here"/><br>
			<?php if(isset($error_n)) echo $error_n; ?>
			password<br>
			<input type="password" name="password" class="place" placeholder="•••••••"/><br>
			<?php if(isset($error_p)) echo $error_p; ?>
			<input type="submit" name="submited" class="submit1" value="Log in!"/>			
		</form>
	</div>
</body>
</html>