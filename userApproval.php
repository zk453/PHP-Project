<?php
include('dbConnector.php');
include('mail.php');
$dbh=connecttoDB();
$result=$dbh->prepare("update companyDB.company_users set is_approved=:approve_status where company_users.username=:username");
$result->bindParam(':username',$_POST['username']);
$result->bindParam(':approve_status',$_POST['status']);
$result->execute();

if($_POST['status']==1){
    $result=$dbh->prepare("SELECT email FROM company_users WHERE company_users.username=:username");
    $result->bindParam(':username',$_POST['username']);
    $result->execute();
    $result=$result->fetchAll();
    $template=file_get_contents("email_templates/accountRequestAccepted.html");
    ob_start();
    handleMail("Your Account has been approved!",$result[0]['email'],$template);
    ob_clean();
}
