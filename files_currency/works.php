<?php
include '../files_database/dbconfig.php';

$user_id = $_SESSION['user_id'];
$stmt = $db_con->prepare("SELECT * FROM currency WHERE user_id=$user_id");
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['bought']))
	{
		$count = $_POST['count'];
		$name = $_POST['code'];
		$rate = (float)str_replace(",",".",$_POST['rate']);
		
		if(($count*$rate)>$results['PLN'] || $count==0)
			$_SESSION['buy_error'] = '<br><span style="color: red; font-size: 20px;">Too low account balance!</span>';
		else
		{
			$currency->log($rate,$name,$count,"BUY");
			$currency->buy($rate,$name,$count);
		}
	}

if(isset($_POST['selled']))
{
	$count = $_POST['count'];
	$name = $_POST['name'];
	$rate = (float)str_replace(",",".",$_SESSION['rate_'.$name]);
	
	if($name!='PLN'){
		if($count>$results[''.$name] || $count==0)
			$_SESSION['sell_error'] = '<br><span style="color: red; font-size: 20px;">Too low currency balance!</span>';
		else
		{
			$currency->log($rate,$name,$count,"SELL");
			$currency->sell($rate,$name,$count);
		}
	}
	else{
		$_SESSION['sell_error'] = '<br><span style="color: red; font-size: 20px;">You can\'t sell PLN!</span>';
	}
}
	
header("Location: ../files_front/index.php");
?>