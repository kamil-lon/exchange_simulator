<?php
  $stmt = $db_con->prepare("SELECT xp FROM users WHERE user_id=$user_id");
  $stmt->execute();
  $results = $stmt->fetch(PDO::FETCH_ASSOC);
  $xp = $results['xp'];
  echo '
  <div class="upper_header" style="text-align: right; padding-right: 200px;">
		Hello '.$user.'! You have '.$xp.' experience points!   |  '.
		'<a class="logout" href = "../files_user/logout.php">Logout</a>
	</div> 
	<div class="lower_header">
		<div class="logo">
			<img src="../files_front/images/logo.png" alt="logo" class="logo2"/>
		</div>
		<div class="menu">
			<a href="../files_front/index.php" style="color: black;"><div class="option">HOME</div>
			<div class="option">NEWS</div>
			<a href="../files_currency/history.php" style="color: black;"><div class="option">HISTORY</div></a>
      <a href="../files_user/ranking.php" style="color: black;"><div class="option">RANKING</div></a>
			<div style="clear: both;"></div>
		</div>
		<div style="clear: both;"></div>
	</div>
  ';
?>