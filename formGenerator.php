<?php

include_once("dbConnector.php");
$dbh=connecttoDB();
$sql="select * from methods";
$result=$dbh->query($sql);
$result=$result->fetchAll();

error_reporting(0);
$selectedMethod;

if(isset($_POST['dimensions'])) {
	echo "<div class='editInput'>";
    echo "<span>Τύπος</span> <div class='sWrapper'><select name='method' id='method'>";
    foreach ($result as $row) {
        $validtresult = $dbh->prepare("select COUNT(1) from " . $row['method'] . " where dimensions=:dimension");
        $validtresult->bindParam(':dimension', $_POST['dimensions']);
        $validtresult->execute();
        $validtresult = $validtresult->fetchAll();
        if ($validtresult[0]['COUNT(1)'] == 1) {
            if (isset($_POST['method']) && $_POST['method'] === $row['method']) {
                echo "<option selected value='" . $row['method'] . "'>" . $row['display_name'] . "</option>";
                $selectedMethod = $row['method'];
            } else {
                if (!isset($selectedMethod)) {
                    $selectedMethod = $row['method'];
                }
                echo "<option value='" . $row['method'] . "'>" . $row['display_name'] . "</option>";
            }
        }
    }
    echo "</select></div>";
    echo "</div>";
}
echo "<div class='editInput'> <span>Χαρτί</span>";
if (isset($selectedMethod)) {
        $sql="select * from ".$selectedMethod." where dimensions=:dimension";
        $result=$dbh->prepare($sql);
        $result->bindParam(':dimension',$_POST['dimensions']);
        $result->execute();
        $result=$result->fetchAll();
		echo "<div class='sWrapper'><select name='printPaper' id='printPaper'>";
		echo "<option disabled selected>--</option>";
        if($result[0]["fine_art"]!=0) {
            if (isset($_POST['printPaper']) && $_POST['printPaper'] === 'fine_art') {
                echo "<option data-cost='".$result[0]["fine_art"]."' value='fine_art' selected=''>Fine Art</option>";
            } else {
                echo "<option data-cost='".$result[0]["fine_art"]."' value='fine_art'>Fine Art</option>";

            }
        }
        if($result[0]["canvas"]!=0) {
            if (isset($_POST['printPaper']) && $_POST['printPaper'] === 'canvas') {
                echo "<option data-cost='".$result[0]["canvas"]."' value='canvas' selected=''>Canvas</option>";
            } else {
                echo "<option data-cost='".$result[0]["canvas"]."' value='canvas'>Canvas</option>";

            }
        }
        if($result[0]["glossy"]!=0) {
            if(isset($_POST['printPaper'])&& $_POST['printPaper']==='glossy'){
                echo "<option data-cost='".$result[0]["glossy"]."' value='glossy' selected=''>Glossy</option>";
            }
            else{
                echo "<option data-cost='".$result[0]["glossy"]."' value='glossy'>Glossy</option>";

            }
        }
        if($result[0]["matte"]!=0) {
            if (isset($_POST['printPaper']) && $_POST['printPaper'] === 'matte') {
                echo "<option data-cost='".$result[0]["matte"]."' value='matte' selected=''>Matte</option>";
            } else {
                echo "<option data-cost='".$result[0]["matte"]."' value='matte'>Matte</option>";

            }
        }
        if($result[0]["silk"]!=0) {
            if (isset($_POST['printPaper']) && $_POST['printPaper'] === 'silk') {
                echo "<option data-cost='".$result[0]["silk"]."' value='silk' selected=''>Silk</option>";
            } else {
                echo "<option data-cost='".$result[0]["silk"]."' value='silk'>Silk</option>";

            }
        }
        if($result[0]["metal"]!=0) {
            if (isset($_POST['printPaper']) && $_POST['printPaper'] === 'metal') {
                echo "<option data-cost='".$result[0]["metal"]."' value='metal' selected=''>Metal</option>";
            } else {
                echo "<option data-cost='".$result[0]["metal"]."' value='metal'>Metal</option>";
            }
        }
        if($result[0]["velvet"]!=0) {
            if (isset($_POST['printPaper']) && $_POST['printPaper'] === 'velvet') {
                echo "<option data-cost='".$result[0]["velvet"]."' value='velvet' selected=''>Velvet</option>";
            } else {
                echo "<option data-cost='".$result[0]["velvet"]."' value='velvet'>Velvet</option>";

            }

        }
		echo "</select></div>";
}
echo "</div>";

	echo "<div class='editInput'> <span>Πλαστικοποίηση</span>";
    $sql="select * from protection_film where dimensions=:dimension;";
    $result=$dbh->prepare($sql);
    $result->bindParam(':dimension',$_POST['dimensions']);
    $result->execute();
    $result=$result->fetchAll();
    echo "<div class='sWrapper'><select name='protectionFilm'>";
    if($_POST['protectionFilm']==="yes"){
        echo "<option data-cost='".$result[0]["price"]."' value='yes' selected>Ναι</option>";
        echo "<option data-cost='0' value='no'>Όχι</option>";
    }else{
        echo "<option data-cost='".$result[0]["price"]."' value='yes'>Ναι</option>";
        echo "<option data-cost='0' value='no' selected>Όχι</option>";
    }
    echo "</select></div>";
    echo "</div>";

    $sql="select * from support_materials where dimensions=:dimension;";
    $result=$dbh->prepare($sql);
    $result->bindParam(':dimension',$_POST['dimensions']);
    $result->execute();
    $result=$result->fetchAll();
	echo "<div class='editInput'> <span>Επικόλληση</span>";
    echo "<div class='sWrapper'><select name='supportMaterials'>";
    echo "<option value='none' data-cost='0.00'>Χωρίς</option>";
    foreach($result[0] as $key=>$value){
        if(!is_numeric($key) && $key!="dimensions") {
            if($key==$_POST['supportMaterials']){
                echo '<option value="' . $key . '" data-cost="' . $value . '" selected="selected">' . ucwords(str_replace('_', " ", $key)) . '</option>';
            }else{
                echo '<option value="' . $key . '" data-cost="' . $value . '">' . ucwords(str_replace('_', " ", $key)) . '</option>';
            }
        }

    }
    echo "</select></div>";
    echo "</div>";

