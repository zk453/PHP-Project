<?php
include_once('dbConnector.php');
include_once('sessionHandler.php');
loginCheck();
$dbh = connecttoDB();
$result=$dbh->prepare("select account_type from companyDB.company_users where company_users.username=:username");
$result->bindParam(":username",$_SESSION['username']);
$result->execute();
$result=$result->fetchAll();
$accountType=$result[0]['account_type'];
?>

<!DOCTYPE html>
<html>
<head>
</head>
<meta charset="UTF-8">
<title>Ανέβασμα Αρχείων</title>
<script
    src="https://code.jquery.com/jquery-3.3.1.js"
    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
    crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/ordersClean.css">
<link rel="stylesheet" type="text/css" href="css/defaultClean.css">
<body>
<div class="topbar">
    <div class="userInfo">
        <?php
            echo "Σύνδεση ως: ".$_SESSION['username'];
        ?>
    </div>
	<form action="logout.php" method="get">
		<input type="submit" class="normalButton orangeGradient" value="Log Out" name="Submit" id="frm1_submit" />
	</form>
</div>
<div class="menu_container">
	<div class="sidebar">
		<img alt="company Logo" src="/resources/images/company name.png">
		<div class="actionsField">
			<?php
			include("actionsField.php");
			?>
		</div>

	</div>
	<div class="orderField">
		<?PHP
		if($accountType == "personal" || $accountType == "commercial"){
			echo "<div class='flexRow'> <div class='flexHead blackGradient'>Order ID</div> <div class='flexHead blackGradient'>Cost</div> <div class='flexHead blackGradient'>Date</div> </div>";
			$result=$dbh->prepare("select * from company_orders where company_orders.for_user=:username order by company_orders.order_date DESC");
			$result->bindParam(":username",$_SESSION['username']);
			$result->execute();
			$result=$result->fetchAll();
			foreach($result as $row){
				echo "<div class='flexRow'><div class='flexData'>".$row['order_code']."</div><div class='flexData'>".$row['total']."</div><div class='flexData'>".$row['order_date']."</div></div>";
			}
		}else{
			echo "<div class='flexRow'> <div class='flexHead blackGradient'>Username</div><div class='flexHead blackGradient'>Order ID</div> <div class='flexHead blackGradient'>Cost</div> <div class='flexHead blackGradient'>Date</div> </div>";
			$result=$dbh->prepare("select * from company_orders order by company_orders.order_date DESC");
			$result->execute();
			$result=$result->fetchAll();
			foreach($result as $row){
				echo "<div class='flexRow'><div class='flexData'>".$row['for_user']."</div><div class='flexData'>".$row['order_code']."</div><div class='flexData'>".$row['total']."</div><div class='flexData'>".$row['order_date']."</div></div>";
			}

		}
		?>

	</div>
</div>
</body>
</html>
