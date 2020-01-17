<?php

include('dbConnector.php');
$passwordHash=hash('sha256',$_POST['passwordInput']);
$username=$_POST['usernameInput'];

$data=array();
$dbh = connecttoDB();
$result=$dbh->prepare("select username,is_approved from companyDB.company_users where company_users.password_hash = :phash and company_users.username=:username");
$result->bindParam(":phash",$passwordHash);
$result->bindParam(":username",$username);
$result->execute();
$result=$result->fetchAll();
if(isset($result[0])){
    session_start();
    if($result[0]['is_approved']=='1'){
        $_SESSION['username']=$username;
        $_SESSION['ipaddress']=$_SERVER['REMOTE_ADDR'];
        $data="loginSuccess";
    }else{
        $data="notApproved";
    }
}else{
    $data="invalidCredentials";
}
echo $data;
