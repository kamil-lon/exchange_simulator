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
		
		$stmt = $this->db->prepare("UPDATE currency set".$name." = ".$name."-$count WHERE user_id=$user_id");
		$stmt->execute();
	}
	
}

?>