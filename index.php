<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>company Online Order manager</title>

    <link rel="stylesheet" href="css/defaultClean.css">
    <link rel="stylesheet" href="css/login.css">
    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>

</head>

<body>
<img alt="company Logo" id="companybanner" src="/resources/images/company name.png">
        <span class="centeredText boldText">Καλώς ήλθατε στο διαδικτυακό <br> σύστημα παραγγελιών μας</span>
<div class="userField">
    <form id="loginForm" action="loginVerification.php" method="POST">
        <span class="centeredText boldText">Ο λογαριασμός μου</span>
        <span id="invalidCredentials" class="errorText error" >Wrong username or password.</span><span id="notApproved" class="errorText error"> Your Account is not approved yet.</span>
        <div class="fieldContainer">
        <div>
			<span class="defaultText">Όνομα χρήστη:</span>
			<input type="text" name="usernameInput" class="defaultText">
		</div>
        <br>
        <div>
			<span class="defaultText">Κωδικός:</span>
			<input type="password" name="passwordInput" class="defaultPass">
		</div>
		<div class="checkbox">
			<input type="checkbox" id="checkbox1" name="rememberMe">
			<label for="checkbox1">Να με θυμάσαι</label>
		</div>
        <br>
		<a href="forgotMyPasword.php">Ξεχάσατε τον κωδικό σας;</a>
		</div>
        <input id="loginButton" type="submit" class="normalButton orangeGradient" value="Σύνδεση">
    </form>
</div>
<div class="registerButtonField">
	<span>Εγγραφή νέου χρήστη</span>
	<form action="register.php" method="post">
    <input type="submit" value="Φόρμα εγγραφής" class="normalButton orangeGradient" name="Submit" id="frm1_submit" />
	</form>
</div>
<script type="text/javascript">
    var testData;
    jQuery(document).ready(function () {
        $("#loginForm").submit(function(event){
            event.preventDefault();
            var form=$(this);
            $.ajax({
                type: 'POST',
                url: 'loginVerification.php',
                data: form.serialize(),
                success: function(data){
                    console.log(data);
                    if(data=="notApproved"){
                        $("#notApproved").removeClass("error")
                    }else{
                        $("#notApproved").addClass("error")
                    }
                    if(data=="invalidCredentials"){
                        $("#invalidCredentials").removeClass("error")
                    }else{
                        $("#invalidCredentials").addClass("error")
                    }
                    if(data=="loginSuccess"){
                        window.location="orders.php";
                    }
                }
            });
        });
    });
</script>
</body>
</html>
