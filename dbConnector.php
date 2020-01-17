<?php

if (!function_exists('connecttoDB')) {
    function connecttoDB()
    {
        try {
            $dbh = new PDO("mysql:host=localhost;dbname=companyDB;charset=utf8", "root", "3VtvqcmMEddIa8x7");
            // set the PDO error mode to exception
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $dbh;
    }
}
?>
