<?php

class Currency
{
	private $db;
	
	function __construct($db_con)
	{
		$this->db = $db_con;
	}
	
	function buy($rate,$name,$count)
	{
		$rate = (float)str_replace(",",".",$rate);
		
		$summ = $rate*$count;
		$user_id = $_SESSION['user_id'];
		
		$stmt = $this->db->prepare("UPDATE currency SET ".$name." = ".$name."+$count WHERE user_id=$user_id");
		$stmt->execute();
		
		$stmt = $this->db->prepare("UPDATE currency SET PLN = PLN-$summ WHERE user_id=$user_id");
		$stmt->execute();
	}
	
	function sell($rate,$name,$count)
	{
		$summ = $rate*$count;
		$user_id = $_SESSION['user_id'];
		
		$stmt = $this->db->prepare("UPDATE currency set PLN = PLN+$summ WHERE user_id=$user_id");
		$stmt->execute();
		
		$stmt = $this->db->prepare("UPDATE currency set $name = $name-$count WHERE user_id=$user_id");
		$stmt->execute();
	}
	
	function log($rate,$name,$count,$type)
	{
		$user_id = $_SESSION['user_id'];
		
		$stmt = $this->db->prepare("SELECT PLN FROM currency WHERE user_id=$user_id");
		$stmt->execute();
		$results = $stmt->fetch(PDO::FETCH_ASSOC);
		$balance_before = $results['PLN'];
		
		$dateTime = new DateTime();
		$date = $dateTime->format('Y-m-d H:i:s');
		
		if($type=="BUY")
		{
			$balance_after = $balance_before-($count*$rate);
			$stmt = $this->db->prepare("INSERT INTO logs VALUES('','$user_id','$balance_before','$balance_after','$date','$name','$count','$type')");
			$stmt->execute();
		}
		
		else if($type=="SELL")
		{
			$balance_after = $balance_before+($count*$rate);
			$stmt = $this->db->prepare("INSERT INTO logs VALUES('','$user_id','$balance_before','$balance_after','$date','$name','$count','$type')");
			$stmt->execute();
		}
	}
	
}

?>