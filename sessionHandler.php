<?php

function loginCheck(){
    session_start();
    if(isset($_SESSION['username'])) {
        if($_SESSION['ipaddress']!=$_SERVER['REMOTE_ADDR']){
            session_destroy();
            header("Location: index.php");
        }
    }else{
        session_destroy();
        header("Location: index.php");
    }
}


function getNames(){
    include("dbConnector.php");
    if(!isset($_SESSION['username'])){
        session_start();
    }
    $dbh = connecttoDB();
    $result=$dbh->prepare("select first_name,last_name,username from companyDB.company_users where company_users.username=:username");
    $result->bindParam(":username",$_SESSION['username']);
    $result->execute();
    $result=$result->fetchAll();
    return $result[0]['first_name']."_".$result[0]['last_name']."_".$result[0]['username'];

}

function getEmail(){
    include("dbConnector.php");
    if(!isset($_SESSION['username'])){
        session_start();
    }
    $dbh = connecttoDB();
    $result=$dbh->prepare("select email from companyDB.company_users where company_users.username=:username");
    $result->bindParam(":username",$_SESSION['username']);
    $result->execute();
    $result=$result->fetchAll();
    return $result[0]['email'];
}

function getFolderPath(){
    include("dbConnector.php");
    if(!isset($_SESSION['username'])){
        session_start();
    }
    $dbh = connecttoDB();
    $result=$dbh->prepare("select variable_value from companyDB.variables where variables.variable_name=:name");
    $name="folder_path";
    $result->bindParam(":name",$name);
    $result->execute();
    $result=$result->fetchAll();
    return $result[0]['variable_value'];

}
?>