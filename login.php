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
			$error_n = '<span style="color: #ffb5d1; font-size: 15px;">User with this nickname or email doesn\'t exists!</span>';
			unset($_SESSION['none']);
		}
		else
		{
			$error_p = '<span style="color: #ffb5d1; font-size: 15px;">Incorrect password!</span>';
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
		<link href="https://fonts.googleapis.com/css?family=Libre+Baskerville:700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Vollkorn" rel="stylesheet">
	</head>

<body>
	<div class="upper_header">
		<form method="post">
		USERNAME OR E-MAIL
		<input type="text" name="login" class="place" placeholder="log@in.here"/>
		PASSWORD
		<input type="password" name="password" class="place" placeholder="•••••••"/>
		<input type="submit" name="submited" class="submit1" value="Log in!"/>		
		<?php if(isset($error_p)) echo $error_p; ?>
		<?php if(isset($error_n)) echo $error_n; ?>
		</form>
	</div>
	<div class="lower_header">
		<div class="logo">
			<img src="logo.png" alt="logo" class="logo2"/>
		</div>
		<div class="menu">
			<div class="option">HOME</div>
			<div class="option">NEWS</div>
			<div class="option">EXCHANGE</div>
			<div class="option">CONTACT</div>
			<div style="clear: both;"></div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<div class="container">
		<div class="baner">
			<img src="baner1.jpg" alt="baner1" class="baner1"/>
		</div>
		<div class="main">
			<div class="welcome">WELCOME!</div>
			<div class="welcome2">Exchange simulator is your place to fun and learn about this biznes</div>
			<div class="register">JOIN US!</div>
			<div class="about">
				<div class="about_cell">
					<div class="about_header">Lorem ipsum</div>
					<div class="about_logo"><img src="about2.png" alt="about2"/></div>
					<div class="about_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut condimentum orci sed cursus gravida. Maecenas sed diam in augue euismod tincidunt. Nunc eget justo ante. Aenean in ornare dolor.</div>
				</div>
				<div class="about_cell" id="about_cell_medium">
					<div class="about_header">Lorem ipsum</div>
					<div class="about_logo"><img src="about2.png" alt="about2"/></div>
					<div class="about_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut condimentum orci sed cursus gravida. Maecenas sed diam in augue euismod tincidunt. Nunc eget justo ante. Aenean in ornare dolor.</div>
				</div>
				<div class="about_cell">
					<div class="about_header">Lorem ipsum</div>
					<div class="about_logo"><img src="about2.png" alt="about2"/></div>
					<div class="about_text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut condimentum orci sed cursus gravida. Maecenas sed diam in augue euismod tincidunt. Nunc eget justo ante. Aenean in ornare dolor.</div>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
	<div class="footer"> &copy; All rights reserved.</div>

</body>
</html>