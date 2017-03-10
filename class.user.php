<?php

class User
{
	private $db;
	
	function __construct($db_con)
	{
		$this->db=$db_con;
	}
	
	public function register($user_name, $user_email, $user_pass)
	{
		try
		{
			$hashed_pass = password_hash($user_pass,PASSWORD_DEFAULT);
			$date = new dateTime();
			$date = $date->format("Y-m-d H:i:s");
			
			$stmt = $this->db->prepare("INSERT INTO users(user_name, user_pass, user_email, add_date) VALUES(:user_name, :user_pass, :user_email, :date)");
			$stmt->bindParam(":user_name",$user_name);
			$stmt->bindParam(":user_pass",$hashed_pass);
			$stmt->bindParam(":user_email",$user_email);
			$stmt->bindParam(":date",$date);
			$stmt->execute();
			
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}

?>
