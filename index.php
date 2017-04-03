<!DOCTYPE HTML>

<?php
include 'dbconfig.php';
$user = $_SESSION['logged'];
$stmt = $db_con->prepare("SELECT * FROM currency WHERE user_id= :user_id");
$stmt->bindParam(":user_id",$_SESSION['user_id']);
$stmt->execute();
$results = $stmt->fetch(PDO::FETCH_ASSOC);

//---------------------------------

$xml = simplexml_load_file('http://www.nbp.pl/kursy/xml/LastA.xml');
$news = simplexml_load_file('http://feeds.bbci.co.uk/news/business/rss.xml');

for($i=0; $i<34; $i++)
{
		$rate = $xml->pozycja[$i]->kurs_sredni;
		$name = $xml->pozycja[$i]->kod_waluty;	
				$_SESSION['rate_'.$name] = $rate;
}


function stack_table($value,$value_name)
{
	if($value>0)
	{
		echo '<tr class="stack_table_row" ><form action="works.php" method="POST" >';
		echo '<td class="stack_table_td" >'.$value_name.'</td>';
		echo '<td>'.$value.'</td>';
		echo '<td><input type="submit" class="sell" name="selled" value="SELL"/></td>';
		echo '<td><input type="text" class="count" name="count"/></td>';
		echo '<input type="hidden" name="name" value="'.$value_name.'"/>';
		echo '</tr></form>';
	}
}
?>

<html>
	<head>
		<title>Enjoy!</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href="https://fonts.googleapis.com/css?family=Libre+Baskerville:700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Vollkorn" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet">
	</head>

<body>
	<div class="upper_header" style="text-align: right; padding-right: 200px;">
		<?php echo 'Hello '.$user."!  |  "; ?>
		<a class="logout" href = "logout.php">Logout</a>
	</div>
	<div class="lower_header">
		<div class="logo">
			<img src="images/logo.png" alt="logo" class="logo2"/>
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
	<div class="index_main">
		<div class="waluty">
				<div class="headers_index">YOUR STACK</div>
				<table class="stack_table">
					<?php 
					stack_table($results['PLN'],'PLN');
					for($i=0; $i<34; $i++)
						{
							$name = $xml->pozycja[$i]->kod_waluty;
							stack_table($results[''.$name],$name);
						}
					?>
				</table>
		</div>
		<div class="kursy">
			<div class="headers_index">RATES</div>
			<table class="rates_table">
					<?php
						for($i=0; $i<35; $i++)
						{
							$rate = $xml->pozycja[$i]->kurs_sredni;
							$name = $xml->pozycja[$i]->nazwa_waluty;
							$code = $xml->pozycja[$i]->kod_waluty;
							echo '<tr><form action="works.php" method="POST">';
							echo '<td class="rates_table_td_name">'.$name.'</td>';
							echo '<td class="rates_table_td_rate" name="rate">'.$rate.'</td>';
							echo '<td><input type="submit" value="BUY" class="buy" name="bought"/></td>';
							echo '<td><input type="text" class="count" name="count"/></td>';
							echo '<input type="hidden" name="rate" value="'.$rate.'" />';
							echo '<input type="hidden" name="code" value="'.$code.'" />';
							echo '</form></tr>';
						}
					?>
			</table>
		</div>
		<div class="news">
			<div class="headers_index">BBS NEWS</div>
						<?php
							for($i=0; $i<9; $i++)
							{
								$title = $news->channel->item[$i]->title;
								$description = $news->channel->item[$i]->description;
								$link = $news->channel->item[$i]->link;
								echo '<div class="one_news">';
								echo '<a href="'.$link.'" target="_blank" class="one_news_a">'.$title.'</a><br>';
								echo $description;
								echo '</div>';
							}
						?>
		</div>
		<div style="clear: both;"></div>
	</div>
	<div class="footer"> &copy; All rights reserved.</div>

</body>
</html>