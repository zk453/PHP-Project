<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>company Online Order manager</title>

    <link rel="stylesheet" href="css/defaultClean.css">
    <link rel="stylesheet" href="css/register.css">
    <script
            src="https://code.jquery.com/jquery-3.3.1.js"
            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
            crossorigin="anonymous"></script>

</head>

<body>
<img alt="company Logo" id="companybanner" src="/resources/images/company name.png">
        <span class="centeredText boldText">Εγγραφή νέου χρήστη</span>
<div class="userField">
    <form id="registerForm" action="registerValidation.php" method="post">
        <div class="registerField">
            <br>
            <div>
            <span class="defaultText">Όνομα χρήστη:</span> 
                       <input type="text" name="usernameInput" class="defaultText">
						<span id="usernameInput" class="errorText error"> Username must have 5 to 20 characters and not contain special symbols.</span><span id="duplicateUsername" class="errorText error"> That username is already taken.</span>
			</div>
            <div>
				<span class="defaultText">Κωδικός:</span>
				<input type="password" name="passwordInput" class="defaultPass">
				<span class="errorText error" id="passwordInput"> Passwords must be identical.</span>
            </div>
            <div>
				<span class="defaultText">Επιβεβαίωση κωδικού:</span>
				<input type="password" name="passwordCInput" class="defaultPass">
            </div>
            <div>
				<span class="defaultText">Email</span>
				<input type="text" name="emailInput" class="defaultText">
				<span id="emailInput" class="errorText error"> Wrong e-mail format.</span>
				<span id="duplicateEmail" class="errorText error"> That e-mail is already in use.</span>
            </div>
            <div>
				<span class="defaultText">Όνομα</span>
				<input type="text" name="firstNameInput" class="defaultText">
				<span id="firstNameInput" class="errorText error"> First name must contain up to 20 Latin or Greek characters.</span>
            </div>
            <div>
				<span class="defaultText">Επώνυμο</span>
				<input type="text" name="lastNameInput" class="defaultText">
				<span id="lastNameInput" class="errorText error"> Last name must contain up to 20 Latin or Greek characters.</span>
            </div>
            <div>
				<span class="defaultText">Τηλέφωνο:</span>
				<input type="text" name="phoneInput" class="defaultText">
				<span id="phoneInput" class="errorText error"> Must contain at least 10 Digits or +.</span>
            </div>
            <div class="typeField">
				<span class="defaultText">Τύπος λογαριασμού:</span>
				<div class="control-group" style="width:9.42em">
					<label class="control control-radio">
						Προσωπικός
						<input type="radio" name="accountTypeInput" checked="" value="personal">
						<div class="control_indicator"></div>
					</label>
					
					<label class="control control-radio">
						Επαγγελματικός
						<input type="radio" name="accountTypeInput" value="commercial">
						<div class="control_indicator"></div>
					</label>
				</div>
            </div>
            <br>
            <div id="accountType">
            <div style="margin-bottom: 0.5em">
				<span class="defaultText">Επαγγελματικό ΑΦΜ:</span>
				<input type="text" name="afmInput" class="defaultText">
				<span id="afmInput" class="errorText error"> Must contain 9 digits</span>
			</div>
            <div>
				<span class="defaultText">Δ.Ο.Υ:</span>
				<input type="text" name="doyInput" class="defaultText"> 
				<span id="doyInput" class="errorText error"> Must contain up to 20 Latin or Greek characters with . or `</span> 
			</div>
            </div>
            <div>
				<span class="defaultText">Prefecture</span>
				<input type="text" name="prefectureInput" class="defaultText">
				<span id="prefectureInput" class="errorText error"> Prefecture must contain up to 20 Latin or Greek characters</span>
            </div>
            <div>
				<span class="defaultText">City</span>
				<input type="text" name="cityInput" class="defaultText">
				<span id="cityInput" class="errorText error"> City must contain up to 30 Latin or Greek characters</span>
            </div>
            <div>
				<span class="defaultText">Zip Code</span>
				<input type="text" name="zipCodeInput" class="defaultText">
				<span id="zipCodeInput" class="errorText error"> Must contain 5 digits</span>
            </div>
            <div>
				<span class="defaultText">Street Address</span>
				<input type="text" name="streetAddressInput" class="defaultText">
				<span id="streetAddressInput" class="errorText error"> Must contain up to 100 Latin or Greek characters and end with the street number.</span>
            </div>
            <div>
            <input type="submit" id="submitButton" class="orangeGradient normalButton" value="Εγγραφή">
        </div>
    </form>
</div>
<script type="text/javascript">
    var testData;
    jQuery(document).ready(function () {
        if($("[name=accountTypeInput]:checked").val()==="commercial"){
            $("#accountType").show();
        }
        else{
            $("#accountType").hide();
            console.log($("[name=accountTypeInput]:checked").val());
        }
        $("[name=accountTypeInput]").click(function(event){
            if($("[name=accountTypeInput]:checked").val()==="commercial"){
                $("#accountType").fadeIn();
            }
            else{
                $("#accountType").fadeOut();
                console.log($("[name=accountTypeInput]:checked").val());
            }
        });
        $("#registerForm").submit(function(event){
            event.preventDefault();
            var form=$(this);
            $.ajax({
                type: 'POST',
                url: 'registerValidation.php',
                data: form.serialize(),
                success: function(data){
                    console.log(data);
                    testData=JSON.parse(data);
                    $('.registerField input').each(function(){
                        if(testData.includes($(this).attr('name'))){
                            $(this).addClass("invalid");
                            $("#"+$(this).attr('name')).removeClass("error");
                        }
                        else{
                            $(this).removeClass("invalid");
                            $("#"+$(this).attr('name')).addClass("error");
                        }
                    });
                    if(testData.includes("duplicateUsername")){
                        $("#duplicateUsername").removeClass("error");
                        $("#registerForm input[name=usernameInput").addClass("invalid");
                    }
                    else{
                        $("#duplicateUsername").addClass("error");
                        $("#registerForm input[name=usernameInput").removeClass("invalid");
                    }
                    if(testData.includes("duplicateEmail")){
                        $("#duplicateEmail").removeClass("error");
                        $("#registerForm input[name=emailInput").addClass("invalid");
                    }
                    else{
                        $("#duplicateEmail").addClass("error");
                        $("#registerForm input[name=emailInput").removeClass("invalid");
                    }
                    if(testData.includes("accountRegistered")){
                        window.location="pendingApproval.html";
                    }
                }
            });
        });
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