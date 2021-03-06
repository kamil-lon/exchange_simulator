<?php
include ('../files_database/dbconfig.php');
$user = $_SESSION['logged'];
$user_id = $_SESSION['user_id'];
$actuallyCurrency = simplexml_load_file('http://api.nbp.pl/api/exchangerates/tables/a?format=xml');
?>
<!DOCTYPE HTML>
<html>
 <head>
   <title>Transaction history</title>
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
    <form method="get">
     <?php
     if(!isset($_GET['offset']))
      $offset = 0;
     else
      $offset = floatval($_GET['offset']);
     
     $userId = $_SESSION['user_id'];
     $stmt = $db_con->prepare("SELECT * FROM logs WHERE user_id = :userId ORDER BY date DESC LIMIT 10 OFFSET :offset");
     $stmt->bindParam(":userId",$userId);
     $stmt->bindParam(":offset",$offset,PDO::PARAM_INT);
     $stmt->execute();
     $count = $stmt->rowCount();
     
     $stmtAll = $db_con->prepare("SELECT * FROM logs WHERE user_id = :userId");
     $stmtAll->bindParam(":userId",$userId);
     $stmtAll->execute();
     $countAll = $stmtAll->rowCount();
     
     echo '<div class="paginate">';
     if($offset>=10)
      echo '<a href="'.$_SERVER['PHP_SELF'].'?offset='.($offset-10).'"> <<< </a>'.'&nbsp&nbsp&nbsp&nbsp&nbsp';
     if($offset+10<=$countAll)
      echo '<a href="'.$_SERVER['PHP_SELF'].'?offset='.($offset+10).'"> >>> </a>';
     echo '</div>';
     
     echo '<table class="history_table">';
     echo '<tr style="font-size: 20px;">
     <td>ID</td><td>PLN before</td>
     <td>PLN after</td><td>Date</td>
     <td>Currency</td><td>Amount</td
     ><td>Type</td></tr>';
     for($i=0; $i<$count; $i++)
     {
      $results = $stmt->fetch(PDO::FETCH_ASSOC);
      if($results['type']=='BUY')
       $fill = 'rgba(187, 94, 237, 0.5)';
      else
       $fill = 'rgba(255, 211, 81, 0.5)';
      echo '<tr style="background-color: '.$fill.';"><td>'.$results['log_id'].'</td>';
      echo '<td>'.$results['balance_before'].'</td>';
      echo '<td>'.$results['balance_after'].'</td>';
      echo '<td>'.$results['date'].'</td>';
      echo '<td>'.$results['currency'].'</td>';
      echo '<td>'.$results['amount'].'</td>';
      echo '<td>'.$results['type'].'</td></tr>';
     }
      echo '</table>';
     ?>
    </form>
   </div>
  </div>
  <div class="footer"> &copy; All rights reserved.</div>
 </body>
</html>