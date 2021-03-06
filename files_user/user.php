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
     
      $stmt = $this->db->prepare("SELECT user_id FROM users WHERE user_name = :user_name");
      $stmt->bindParam(":user_name",$user_name);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $user_id = $result['user_id'];
     
      $stmt = $this->db->prepare("INSERT INTO currency(user_id) VALUES(:user_id)");
      $stmt->bindParam(":user_id",$user_id);
      $stmt->execute();
      
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	
	public function login($user_name, $user_email, $user_pass)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM users WHERE user_name = :user_name OR user_email = :user_email");
			$stmt->execute(array(':user_name'=>$user_name, ':user_email'=>$user_email));

			if($stmt->rowCount()>0)
			{
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				if(password_verify($user_pass,$result['user_pass']))
				{
					$_SESSION['logged'] = $result['user_name'];
					$_SESSION['user_id'] = $result['user_id'];
          $_SESSION['xp'] = $result['xp'];
          $user_id = $_SESSION['user_id'];
          
          if(!isset($_COOKIE['dailyXpGave'.$user_id]))
          {
            $stmt = $this->db->prepare("UPDATE users SET xp=xp+10 WHERE user_id=$user_id");
			      $stmt->execute();
            setcookie('dailyXpGave'.$user_id,true,time()+24*3600);
          }   
            
					return true;
				}
				else
					return false;
			}
			else
			{
				$_SESSION['none'] = true;
				return false;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
}

?>
