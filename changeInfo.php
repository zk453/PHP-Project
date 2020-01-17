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

<form id="changeForm" action="changeValidation.php" method="post">
    <?PHP
    $dbh=connecttoDB();
    $result=$dbh->prepare("select * from company_users where company_users.username=:username");
    $result->bindParam(":username",$_SESSION['username']);
    $result->execute();
    $result=$result->fetchAll();
    echo "<br>";
    echo "<span class=\"defaultText\">Password</span><span class=\"errorText error\" id=\"passwordInput\"> Passwords must be identical.</span>";
    echo "<br>";
    echo "<input type=\"password\" name=\"passwordInput\" class=\"defaultPass\">";
    echo "<br>";
    echo "<span class=\"defaultText\">Password Confirmation</span>";
    echo "<br>";
    echo "<input type=\"password\" name=\"passwordCInput\" class=\"defaultPass\">";
    echo "<br>";
    echo "<span class=\"defaultText\">First Name</span><span id=\"firstNameInput\" class=\"errorText error\"> First name must contain up to 20 Latin or Greek characters.</span>";
    echo "<br>";
    echo "<input type=\"text\" name=\"firstNameInput\" class=\"defaultText\" value='".$result[0]['first_name']."'>";
    echo "<br>";
    echo "<span class=\"defaultText\">Last Name</span><span id=\"lastNameInput\" class=\"errorText error\"> Last name must contain up to 20 Latin or Greek characters.</span>";
    echo "<br>";
    echo "<input type=\"text\" name=\"lastNameInput\" class=\"defaultText\" value='".$result[0]['last_name']."'>";
    echo "<br>";
    echo "<span class=\"defaultText\">Prefecture</span><span id=\"prefectureInput\" class=\"errorText error\"> Prefecture must contain up to 20 Latin or Greek characters</span>";
    echo "<br>";
    echo "<input type=\"text\" name=\"prefectureInput\" class=\"defaultText\" value='".$result[0]['prefecture']."'>";
    echo "<br>";
    echo "<span class=\"defaultText\">City</span><span id=\"cityInput\" class=\"errorText error\"> City must contain up to 30 Latin or Greek characters</span>";
    echo "<br>";
    echo "<input type=\"text\" name=\"cityInput\" class=\"defaultText\" value='".$result[0]['city']."'>";
    echo "<br>";
    echo "<span class=\"defaultText\">Zip Code</span><span id=\"zipCodeInput\" class=\"errorText error\"> Must contain 6 digits</span>";
    echo "<br>";
    echo "<input type=\"text\" name=\"zipCodeInput\" class=\"defaultText\" value='".$result[0]['zip_code']."'>";
    echo "<br>";
    echo "<span class=\"defaultText\">Street Address</span><span id=\"streetAddressInput\" class=\"errorText error\"> Must contain up to 100 Latin or Greek characters and end with the street number.</span>";
    echo "<br>";
    echo "<input type=\"text\" name=\"streetAddressInput\" class=\"defaultText\" value='".$result[0]['street_address']."'>";
    echo "<br>";
    echo "<span class=\"defaultText\">Phone Input</span><span id=\"phoneInput\" class=\"errorText error\"> Must contain at least 10 Digits or +.</span>";
    echo "<br>";
    echo "<input type=\"text\" name=\"phoneInput\" class=\"defaultText\" value='".$result[0]['phone']."'>";
    echo "<br>";
    if($accountType == "commercial"){
        echo "<span class=\"defaultText\">AFM</span><span id=\"afmInput\" class=\"errorText error\"> Must contain 9 digits</span>";
        echo "<br>";
        echo "<input type=\"text\" name=\"afmInput\" class=\"defaultText\" value='".$result[0]['afm']."'>";
        echo "<br>";
        echo "<span class=\"defaultText\">DOY</span><span id=\"doyInput\" class=\"errorText error\"> Must contain up to 20 Latin or Greek characters with . or </span>";
        echo "<br>";
        echo "<input type=\"text\" name=\"doyInput\" class=\"defaultText\" value='".$result[0]['doy']."'>";
    }
    echo "<br>";
    echo "<input type=\"submit\" id=\"submitButton\" value=\"submit\">";
    ?>
</form>
</div>

</body>
</html>
<script type="text/javascript">
    $("#changeForm").submit(function(event){
        event.preventDefault();
        var form=$(this);
        $.ajax({
            type: 'POST',
            url: 'changeValidation.php',
            data: form.serialize(),
            success: function(data){
                console.log(data);
                testData=JSON.parse(data);
                $('input').each(function(){
                    if(testData.includes($(this).attr('name'))){
                        $(this).addClass("invalid");
                        $("#"+$(this).attr('name')).removeClass("error");
                    }
                    else{
                        $(this).removeClass("invalid");
                        $("#"+$(this).attr('name')).addClass("error");
                    }
                });
                if(testData.includes("changeApproved")){
                    window.location="changeInfo.php";
                }
            }
        });
    });
</script>
