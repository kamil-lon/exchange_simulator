<?php
require_once '../files_database/dbconfig.php';

if(isset($_POST['submited']))
{
	$user_name = trim($_POST['user_name']);
	$user_mail = trim($_POST['user_mail']);
	$user_pass = trim($_POST['user_pass']);
	$user_pass2 = trim($_POST['user_pass2']);
	$ok=true;
	
	if(strlen($user_name)<3 || strlen($user_name)>25)
	{
		$error_n = '<span style="color: red; font-size: 15px;">Nickname must have between 3 and 25 symbols!</span><br>';
		$ok=false;
	}
	if((!filter_var($user_mail, FILTER_VALIDATE_EMAIL)) || $user_mail=='')
	{
		$error_m = '<span style="color: red; font-size: 15px;">Enter a valid email address!</span><br>';
		$ok=false;
	}
	if(strlen($user_pass)<6 || strlen($user_pass)>35)
	{
		$error_p = '<span style="color: red; font-size: 15px;">Password must have between 6 and 35 symbols!</span><br>';
		$ok=false;
	}
	if(!isset($_POST['accept']))
	{
		$error_a = '<span style="color: red; font-size: 15px;">Accept checkbox!</span><br>';
		$ok=false;
	}
  if($user_pass!=$user_pass2)
	{
		$error_p2 = '<span style="color: red; font-size: 15px;">Passwords are not the same!</span><br>';
		$ok=false;
	}

	if($ok)
	{
		try
  {
   $stmt = $db_con->prepare("SELECT user_name,user_email FROM users WHERE user_name = :user_name OR user_email = :user_mail");
   $stmt->execute(array(':user_name'=>$user_name, ':user_mail'=>$user_mail));
   $result=$stmt->fetch(PDO::FETCH_ASSOC);

    if($result['user_name']==$user_name)
     $error_n = '<span style="color: red; font-size: 15px;">This nickname is already taken!</span><br>';
			
    else if($result['user_email']==$user_mail)
     $error_m = '<span style="color: red; font-size: 15px;">This e-mail is already taken!</span><br>';
			
    else
     if($user->register($user_name,$user_mail,$user_pass))
						header('Location: ../files_user/login.php');
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();			
		}
	}
	
}

?>

<!DOCTYPE HTML>
<html>
			<head>
			<title>Register!</title>
			<meta charset="UTF-8">
			<meta name="viewport" content="initial-scale=1.0">
			<link rel="stylesheet" type="text/css" href="../files_front/style.css">
      <link href="https://fonts.googleapis.com/css?family=Noto+Serif" rel="stylesheet">
			</head>
	
			<body>
					<div class="mainReg">
						<form method="post">
							nickname:<br>
							<input type="text" class="placeReg" name="user_name" placeholder="Set Your Nickname"/><br>
							<?php if(isset($error_n)) echo $error_n; ?>
							e-mail<br>
							<input type="text" class="placeReg" name="user_mail" placeholder="example@dot.com"/><br>
							<?php if(isset($error_m)) echo $error_m; ?>
							password<br>
							<input type="password" class="placeReg" name="user_pass" placeholder="••••••••"/><br>
							<?php if(isset($error_p)) echo $error_p; ?>
              password again
              <input type="password" class="placeReg" name="user_pass2" placeholder="••••••••"/><br>
              <?php if(isset($error_p2)) echo $error_p2; ?>
							Accept <a href="statutes.html">statutes</a>
							<input type="checkbox" name="accept"/><br>
							<?php if(isset($error_a)) echo $error_a; ?>
							<input type="submit" name="submited" class="submitReg" value="Register!"/>
						</form>
					</div>
			</body>
</html>