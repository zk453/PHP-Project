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
			?></button>
		</div>

	</div>
	<div class="orderField">
		<?PHP
		if($accountType == "admin"){
			echo "<div class='flexRow'> 
			<div class='flexHead blackGradient'>Username</div> 
			<div class='flexHead blackGradient'>First Name</div> 
			<div class='flexHead blackGradient'>Last name</div>
			<div class='flexHead blackGradient'>Mail</div> 
			<div class='flexHead blackGradient'>Phone</div> 
			<div class='flexHead blackGradient'>Afm</div> 
			<div class='flexHead blackGradient' style='flex:2;'></div> 
			</div>";
			$result=$dbh->prepare("select * from company_users where company_users.is_approved=0");
			$result->bindParam(":username",$_SESSION['username']);
			$result->execute();
			$result=$result->fetchAll();
			foreach($result as $row){
				echo "<td></td></tr>";
				echo "
				<div class='flexRow'>
				<div class='flexData'>".$row['username']."</div> 
				<div class='flexData'>".$row['first_name']."</div>
				<div class='flexData'>".$row['last_name']."</div>
				<div class='flexData'>".$row['email']."</div>
				<div class='flexData'>".$row['phone']."</div>
				<div class='flexData'>".$row['afm']."</div>
				<div class='flexButtons' style='flex:2;'>
					<button class='orangeGradient normalButton approveButton' name='".$row['username']."' value='".$row['username']."' >Approve User</button>
					<button class='blackGradient normalButton disapproveButton' name='".$row['username']."' value='".$row['username']."' >Disapprove User</button>
				</div>
				</div>";
				
			}
		}
		?>

	</div>
</div>
</body>
</html>
<script type="text/javascript">
    jQuery(document).ready(function(){
        $(document).on("click",".approveButton" ,function(){
            var button=$(this);
            $.ajax({
                url: "userApproval.php",
                type: "POST",
                data: { 'username' : $(this).attr('name') , 'status' : 1 },
                success: function (result) {
                    button.parent().parent().remove();
                }
            });
        });
    });
    jQuery(document).ready(function(){
        $(document).on("click",".disapproveButton" ,function(){
            var button=$(this);
            $.ajax({
                url: "userApproval.php",
                type: "POST",
                data: { 'username' : $(this).attr('name') , 'status' : 2 },
                success: function (result) {
                    button.parent().parent().remove();
                }
            });
        });
    });

</script>
