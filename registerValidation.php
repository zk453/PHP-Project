<?php

include('dbConnector.php');
include('mail.php');
$data=array();
if(! filter_var($_POST['emailInput'],FILTER_VALIDATE_EMAIL)){
    array_push($data,"emailInput");
}
if(isset($_POST['emailInput'])){
    $dbh = connecttoDB();
    $result=$dbh->prepare("select email from companyDB.company_users where company_users.email = :email");
    $result->bindParam(":email",$_POST['emailInput']);
    $result->execute();
    $result=$result->fetchAll();
    if(sizeof($result)>0){
        array_push($data,"duplicateEmail");
    }


}
if(! filter_var($_POST['usernameInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^\w{5,20}$/")))) {
    array_push($data, "usernameInput");
}
if(isset($_POST['usernameInput'])){
    $dbh = connecttoDB();
    $result=$dbh->prepare("select username from companyDB.company_users where company_users.username = :username");
    $result->bindParam(":username",$_POST['usernameInput']);
    $result->execute();
    $result=$result->fetchAll();
    if(sizeof($result)>0){
        array_push($data,"duplicateUsername");
    }

}
if(! filter_var($_POST['passwordInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^".(string)$_POST['passwordCInput']."$/")))){
    array_push($data,"passwordInput");
}
if(! filter_var($_POST['firstNameInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z\p{Greek}\s]{0,20}$/u")))){
    array_push($data,"firstNameInput");
}
if(! filter_var($_POST['lastNameInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z\p{Greek}\s]{0,20}$/u")))){
    array_push($data,"lastNameInput");
}

if(! filter_var($_POST['prefectureInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z\p{Greek}\s]{0,20}$/u")))){
    array_push($data,"prefectureInput");
}
if(! filter_var($_POST['cityInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z\p{Greek}\s]{0,30}$/u")))){
    echo $_POST['cityInput'];
    array_push($data,"cityInput");
}
if(! filter_var($_POST['zipCodeInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[0-9]{5}$/")))){
    array_push($data,"zipCodeInput");
}
if(! filter_var($_POST['phoneInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[0-9+]{10,15}$/")))){
    array_push($data,"phoneInput");
}
if(! filter_var($_POST['streetAddressInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^([A-Za-z\p{Greek}]+\s?)+[0-9]+$/u")))){
    array_push($data,"streetAddressInput");
}elseif(strlen($_POST['streetAddressInput'])>100){
    array_push($data,"streetAddressInput");
}
if(filter_var($_POST['accountTypeInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^commercial$/")))) {
    if (!filter_var($_POST['afmInput'], FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[0-9]{9}$/")))) {
        array_push($data,"afmInput");
    }
    if(! filter_var($_POST['doyInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^([A-Za-z\p{Greek}.`]+\s?)+$/u")))){
        array_push($data,"doyInput");
    }elseif(strlen($_POST['doyInput'])>20){
        array_push($data,"doyInput");
    }
}
if(sizeof($data)==0){
    array_push($data,"accountRegistered");
    $dbh=connecttoDB();
    $result=$dbh->prepare("insert into companyDB.company_users(`username`,`password_hash`,`email`,`first_name`,`last_name`,`street_address`,`zip_code`,`doy`,`afm`,`prefecture`,`is_approved`,`account_type`,`city`,`phone`) values(:username, :password_hash, :email, :first_name, :last_name, :street_address, :zip_code, :doy, :afm, :prefecture, 0, :account_type, :city, :phone)");

    $hash=hash('sha256',$_POST['passwordInput']);
    $result->bindParam(':username',$_POST['usernameInput']);
    $result->bindParam(':password_hash',$hash);
    $result->bindParam(':email',$_POST['emailInput']);
    $result->bindParam(':first_name',$_POST['firstNameInput']);
    $result->bindParam(':last_name',$_POST['lastNameInput']);
    $result->bindParam(':street_address',$_POST['streetAddressInput']);
    $result->bindParam(':zip_code',$_POST['zipCodeInput']);
    $result->bindParam(':doy',$_POST['doyInput']);
    if(isset($_POST['afmInput'])){
        $tempval=000000000;
        $result->bindParam(':afm',$tempval);
    }else{
        $result->bindParam(':afm',$_POST['afmInput']);
    }
    $result->bindParam(':prefecture',$_POST['prefectureInput']);
    $result->bindParam(':account_type',$_POST['accountTypeInput']);
    $result->bindParam(':city',$_POST['cityInput']);
    $result->bindParam(':phone',$_POST['phoneInput']);
    $result->execute();
    $template=file_get_contents("email_templates/accountRequest.html");
    $template2=file_get_contents("email_templates/accountRequestPending.html");
    foreach( filter_var_array($_POST,FILTER_SANITIZE_STRING) as $key=>$value){
        $template=str_replace('{{ '.$key.' }}',$value,$template);
        $template2=str_replace('{{ '.$key.' }}',$value,$template2);
    }
    $result=$dbh->prepare("SELECT email FROM company_users WHERE company_users.account_type='moderator' OR company_users.account_type='admin'");
    $result->execute();
    $result=$result->fetchAll();
    ob_start();
    foreach ($result as $key=>$value){
        handleMail("New Account Request",$value['email'],$template);
    }
    handleMail("New Account, Pending Approval",filter_var($_POST['emailInput'],FILTER_SANITIZE_EMAIL),$template2);
    ob_clean();
}
echo json_encode($data);
