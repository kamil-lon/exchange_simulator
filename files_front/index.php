<!DOCTYPE HTML>

<?php
include '../files_database/dbconfig.php';
if(!isset($_SESSION['logged']))
 header("Location: ../files_user/login.php");
$user = $_SESSION['logged'];
$user_id = $_SESSION['user_id'];

//---------------------------------
$actuallyCurrency = simplexml_load_file('http://api.nbp.pl/api/exchangerates/tables/a?format=xml');
$news = simplexml_load_file('http://feeds.bbci.co.uk/news/business/rss.xml');

$lastDate = date("Y-m-d",strtotime("-1 days"));
$url = 'http://api.nbp.pl/api/exchangerates/tables/a/'.$lastDate.'?format=xml';

if((!(@simplexml_load_file('http://api.nbp.pl/api/exchangerates/tables/a/'.date("Y-m-d").'?format=xml'))) && 
 (@simplexml_load_file('http://api.nbp.pl/api/exchangerates/tables/a/'.date("Y-m-d",strtotime("-1 days")).'?format=xml')))
{
  $lastDate = date("Y-m-d",strtotime("-2 days"));
  $url = 'http://api.nbp.pl/api/exchangerates/tables/a/'.$lastDate.'?format=xml';
  $lastCurrency = @simplexml_load_file($url);
}

$lastCurrency = @simplexml_load_file($url);
if(!$lastCurrency)
{
 $i=2;
 while(!$lastCurrency)
 {
  $lastDate = date("Y-m-d",strtotime("-$i days"));
  $url = 'http://api.nbp.pl/api/exchangerates/tables/a/'.$lastDate.'?format=xml';
  $lastCurrency = @simplexml_load_file($url); 
  $i++;
 } 
}

//session variables named as currency necessary to make sell
for($i=0; $i<34; $i++)
{
		$rate = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Mid;
		$code = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Code;
    $name = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Currency;	
		$_SESSION['rate_'.$code] = $rate;
		$_SESSION['name_'.$code] = $name;
    $_SESSION['code_'.$code] = $code;
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
	<?php include 'header.php'; ?>
	<div class="index_main">
		<div class="waluty">
				<div class="headers_index">YOUR STACK
			<?php if(isset($_SESSION['sell_error'])) { 
								echo $_SESSION['sell_error']; unset($_SESSION['sell_error']); } ?>
			</div>
				<table class="stack_table">
					<?php 
					$currency->stack_table();
					?>
				</table>
		</div>
		<div class="biggest">
			<div class="headers_index">RATES
			<?php if(isset($_SESSION['buy_error'])) { 
								echo $_SESSION['buy_error']; unset($_SESSION['buy_error']); } ?>
			</div>
			<table class="rates_table">
					<?php
            $sorted = array();
						for($i=0; $i<34; $i++)
						{
							$rate = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Mid;
							$name = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Currency;	
							$code = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Code;	
							$rate = round((float)str_replace(",",".",$rate),4);
							
							$rateYesterday = (string)$lastCurrency->ExchangeRatesTable->Rates->Rate[$i]->Mid;
							$rateYesterday = round((float)str_replace(",",".",$rateYesterday),4);
							$change = round(abs($rate-$rateYesterday),4);
							
							
							$sortedChange[$i] = $change;
							$sortedCodes[$i] = $code;
            }
            
            for($i=0; $i<34; $i++)
              for($j=0; $j<$i; $j++)
              {
                if($sortedChange[$j]<$sortedChange[$i])
                {
                  $helpChange = $sortedChange[$j];
                  $sortedChange[$j] = $sortedChange[$i];
                  $sortedChange[$i] = $helpChange;
                  
                  $helpCode = $sortedCodes[$j];
                  $sortedCodes[$j] = $sortedCodes[$i];
                  $sortedCodes[$i] = $helpCode;
                }
              }
        
            for($i=0; $i<34; $i++)
						{
              $rate = (string)$actuallyCurrency->ExchangeRatesTable->Rates->Rate[$i]->Mid;
              $rate = round((float)str_replace(",",".",$rate),4);
              $rateYesterday = (string)$lastCurrency->ExchangeRatesTable->Rates->Rate[$i]->Mid;
							$rateYesterday = round((float)str_replace(",",".",$rateYesterday),4);
              
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
								$arrow='';}
              
              $code = $sortedCodes[$i];
              $change = $sortedChange[$i];
							echo '<tr><form action="../files_currency/works.php" method="POST">';
							echo '<td class="rates_table_td_name">'.$_SESSION['name_'.$code].'</td>';
							echo '<td class="rates_table_td_rate" name="rate">'.$_SESSION['rate_'.$code].'</td>';
							echo '<td class="rates_table_td_arrow">'.$arrow.'</td>';
							echo '<td class="rates_table_td_change" style="'.$style.'">'.$change.'</td>';
							echo '<td><input type="submit" value="BUY" class="buy" name="bought"/></td>';
							echo '<td><input type="text" class="count" name="count"/></td>';
							echo '<input type="hidden" name="rate" value="'.$_SESSION['rate_'.$code].'" />';
							echo '<input type="hidden" name="code" value="'.$_SESSION['code_'.$code].'" />';
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