<?php

include('dbConnector.php');
include('mail.php');
session_start();
$data=array();
if($_POST['passwordInput']!=""){
    if(! filter_var($_POST['passwordInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^".(string)$_POST['passwordCInput']."$/")))){
        array_push($data,"passwordInput");
    }
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
if(! filter_var($_POST['zipCodeInput'],FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[0-9]{6}$/")))){
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

$dbh = connecttoDB();
$result=$dbh->prepare("select account_type from companyDB.company_users where company_users.username=:username");
$result->bindParam(":username",$_SESSION['username']);
$result->execute();
$result=$result->fetchAll();
$accountType=$result[0]['account_type'];

if($accountType=="commercial") {
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
    array_push($data,"changeApproved");
    $dbh=connecttoDB();
    if($_POST['passwordInput']!=""){
        $result=$dbh->prepare("update companyDB.company_users set `password_hash`=:password_hash,`first_name`=:first_name,`last_name`=:last_name,`street_address`=:street_address,`zip_code`=:zip_code,`doy`=:doy,`afm`=:afm,`prefecture`=:prefecture,`city`=:city,`phone`=:phone where company_users.username=:username");
        $hash=hash('sha256',$_POST['passwordInput']);
        $result->bindParam(':username',$_POST['usernameInput']);
        $result->bindParam(':password_hash',$hash);
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
        $result->bindParam(':city',$_POST['cityInput']);
        $result->bindParam(':phone',$_POST['phoneInput']);
        $result->execute();
    }else{
        $result=$dbh->prepare("update companyDB.company_users set `first_name`=:first_name,`last_name`=:last_name,`street_address`=:street_address,`zip_code`=:zip_code,`doy`=:doy,`afm`=:afm,`prefecture`=:prefecture,`city`=:city,`phone`=:phone where company_users.username=:username");
        $hash=hash('sha256',$_POST['passwordInput']);
        $result->bindParam(':username',$_POST['usernameInput']);
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
        $result->bindParam(':city',$_POST['cityInput']);
        $result->bindParam(':phone',$_POST['phoneInput']);
        $result->execute();

    }
}
echo json_encode($data);
