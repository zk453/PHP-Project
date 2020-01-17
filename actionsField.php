<?php
$currentPage=basename($_SERVER['PHP_SELF']);

if($accountType == "personal" || $accountType=='commercial') {
	echo '
	<form action="uploadInterface.php" method="get">
	<input type="submit" class="normalButton orangeGradient ';
	if($currentPage != "uploadInterface.php"){
		echo 'disabledGradient';
	}
	echo ' " value="New Order" name="Submit" id="frm1_submit" />
	</form>';
	echo '
	<form action="changeInfo.php" method="get">
	<input type="submit" class="normalButton orangeGradient ';
	if($currentPage != "changeInfo.php"){
		echo 'disabledGradient';
	}
	echo ' " value="Change Information" name="Submit" id="frm2_submit" />
	</form>';
	echo '
	<form action="orders.php" method="get">
	<input type="submit" class="normalButton orangeGradient ';
	if($currentPage != "orders.php"){
		echo 'disabledGradient';
	}
	echo ' " value="My Orders" name="Submit" id="frm3_submit" />
	</form>';
}elseif($accountType == "moderator" ){
	echo '
	<form action="orders.php" method="get">
	<input type="submit" class="normalButton orangeGradient ';
	if($currentPage != "orders.php"){
		echo 'disabledGradient';
	}
	echo ' " value="Orders" name="Submit" id="frm3_submit" />
	</form>';
	echo '
	<form action="orders.php" method="get">
	<input type="submit" class="normalButton orangeGradient" value="My Orders" name="Submit" id="frm2_submit" />
	</form>';
}
if($accountType == "admin"){
	echo '
	<form action="approveUsers.php" method="get">
	<input type="submit" class="normalButton orangeGradient ';
	if($currentPage == "approveUsers.php"){
		echo 'disabledGradient';
	}
	echo ' " value="Approve Users" name="Submit" id="frm3_submit" />
	</form>';
	echo '
	<form action="orders.php" method="get">
	<input type="submit" class="normalButton orangeGradient ';
	if($currentPage == "orders.php"){
		echo 'disabledGradient';
	}
	echo ' " value="Orders" name="Submit" id="frm3_submit" />
	</form>';
}
?>
