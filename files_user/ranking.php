<?php
include ('../files_database/dbconfig.php');
$user = $_SESSION['logged'];
$user_id = $_SESSION['user_id'];
$actuallyCurrency = simplexml_load_file('http://api.nbp.pl/api/exchangerates/tables/a?format=xml');
?>
<!DOCTYPE HTML>
<html>
 <head>
   <title>Top 10</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="initial-scale=1.0">
   <link rel="stylesheet" type="text/css" href="../files_front/style.css">
   <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville:700" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css?family=Vollkorn" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet">
 </head>
 <body>
  <?php include '../files_front/header.php'; ?>
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
    <div class="biggest" style="width: 900px;">
      <?php
        echo '<div class="rankingOptions">';
        echo '<a href="'.$_SERVER['PHP_SELF'].'?by=xp'.'"><div class="rankingOption">by xp</div></a>';
        echo '<a href="'.$_SERVER['PHP_SELF'].'?by=PLN'.'"><div class="rankingOption">by pln</div></a>';
        echo '<div style="clear: both;"></div>';
        echo '</div>';
        if(!isset($_GET['by']))
          $by = 'xp';
        else
          $by = $_GET['by'];
        if($by=='xp')
        {
          $stmt = $db_con->prepare("SELECT * FROM users ORDER BY xp DESC");
          $stmt->execute();
          $count = $stmt->rowCount();
        }
        else if($by=='PLN')
        {
          $stmt = $db_con->prepare("SELECT user_name,PLN FROM users,currency WHERE users.user_id=currency.user_id ORDER BY PLN DESC");
          $stmt->execute();
          $count = $stmt->rowCount();
        }
        echo '<table class="rankingTable">';
        echo '<tr>';
        echo '<td>No</td>';
        echo '<td>Name</td>';
        echo '<td>'.$by.'</td>';
        echo '</tr>';
        for($i=0; $i<$count; $i++)
        {
          $results = $stmt->fetch(PDO::FETCH_ASSOC);
          echo '<tr>';
          echo '<td>'.($i+1).'</td>';
          echo '<td>'.$results['user_name'].'</td>';
          echo '<td>'.$results[$by].'</td>';
          echo '</tr>';
        }
        echo '</table>';
      ?>
      </div>
  </div>
  <div class="footer"> &copy; All rights reserved.</div>
 </body>
</html>