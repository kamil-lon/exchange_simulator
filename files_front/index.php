<!DOCTYPE HTML>

<?php
include 'files_database/dbconfig.php';
$user = $_SESSION['logged'];
$stmt = $db_con->prepare("SELECT * FROM currency WHERE user_id= :user_id");
$stmt->bindParam(":user_id",$_SESSION['user_id']);
$stmt->execute();
$results = $stmt->fetch(PDO::FETCH_ASSOC);

//---------------------------------
$lastDate = date("Y-m-d",strtotime("-1 days"));
$url = 'http://api.nbp.pl/api/exchangerates/tables/a/'.$lastDate.'?format=xml';
if(!is_null($url))
{
	$lastCurrency = simplexml_load_file($url);
}
else
{
	$lastDate = date("Y-m-d",strtotime("-2 days"));
	if(file_exists('http://api.nbp.pl/api/exchangerates/tables/a/'.$lastDate.'?format=xml'))
		$lastCurrency = simplexml_load_file('http://api.nbp.pl/api/exchangerates/tables/a/'.$lastDate.'?format=xml');
	else
	{
		$lastDate = date("Y-m-d",strtotime("-3 days"));
		$lastCurrency = simplexml_load_file('http://api.nbp.pl/api/exchangerates/tables/a/'.$lastDate.'?format=xml');
	}
}

$actuallyCurrency = simplexml_load_file('http://api.nbp.pl/api/exchangerates/tables/a?format=xml');
$news = simplexml_load_file('http://feeds.bbci.co.uk/news/business/rss.xml');

//session variables named as currency necessary to make sell
for($i=0; $i<34; $i++)
{
		$rate = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Mid;
		$code = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Code;	
		$_SESSION['rate_'.$code] = $rate;
}

//-----------------------------------

function stack_table($value,$value_name)
{
	if($value!=0)
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
			<div class="option">HISTORY</div>
			<div class="option">CONTACT</div>
			<div style="clear: both;"></div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<div class="index_main">
		<div class="waluty">
				<div class="headers_index">YOUR STACK
			<?php if(isset($_SESSION['sell_error'])) { 
								echo $_SESSION['sell_error']; unset($_SESSION['sell_error']); } ?>
			</div>
				<table class="stack_table">
					<?php 
					stack_table($results['PLN'],'PLN');
					for($i=0; $i<34; $i++)
						{
							$code = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Code;	
							stack_table($results[''.$code],$code);	
						}
					?>
				</table>
		</div>
		<div class="kursy">
			<div class="headers_index">RATES
			<?php if(isset($_SESSION['buy_error'])) { 
								echo $_SESSION['buy_error']; unset($_SESSION['buy_error']); } ?>
			</div>
			<table class="rates_table">
					<?php
						for($i=0; $i<35; $i++)
						{
							$rate = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Mid;
							$name = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Currency;	
							$code = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Code;	
							$rate = round((float)str_replace(",",".",$rate),4);
							
							
							$rateYesterday = (string)$lastCurrency->ExchangeRatesTable->Rates->Rate[$i]->Mid;
							$rateYesterday = round((float)str_replace(",",".",$rateYesterday),4);
							$change = round(abs($rate-$rateYesterday),4);
							
							if($rate>$rateYesterday){
								$style = 'color: green;';
								$arrow = '<img src="images/arrow_up.png" height="10px" width="10px" alt=""/>';
							}
							else if($rate<$rateYesterday){
								$style = 'color: red';
								$arrow = '<img src="images/arrow_down.png" height="10px" width="10px" alt=""/>';
							}
							else{
								$style='color: blue;';
								$arrow='';
							}
							
							echo '<tr><form action="works.php" method="POST">';
							echo '<td class="rates_table_td_name">'.$name.'</td>';
							echo '<td class="rates_table_td_rate" name="rate">'.$rate.'</td>';
							echo '<td class="rates_table_td_arrow">'.$arrow.'</td>';
							echo '<td class="rates_table_td_change" style="'.$style.'">'.$change.'</td>';
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