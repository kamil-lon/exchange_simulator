<?php
include 'dbconfig.php';

if(isset($_POST['bought']))
	{
		$count = $_POST['count'];
		$rate = $_POST['rate'];
		$code = $_POST['code'];
		$rate = (float)str_replace(",",".",$rate);

		$currency->buy($rate,$code,$count);
	}

if(isset($_POST['selled']))
{
	$count = $_POST['count'];
	$name = $_POST['name'];
	$rate = $_SESSION['rate_'.$name];
	
	echo $count.'	'.$name.'	'.$rate;
}
	
header("Location: index.php");
?>