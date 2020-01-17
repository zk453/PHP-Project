<?php
include_once("sessionHandler.php");
include_once("dbConnector.php");
include_once("mail.php");
require_once('vendor/autoload.php');
function edit($x,$y,$width,$height,$rotation,$source,$destination){
    $im=new Imagick($source);
    $final = $im->clone();
    $final->setImageBackgroundColor("white");
    $final->rotateImage("#FFFFFFFF",(float)$rotation);
    $final->borderImage('white',$final->getImageWidth(),$final->getImageHeight());
    $final->cropImage($width, $height, $x+$final->getImageWidth()/3, $y+$final->getImageHeight()/3);
    $final->writeImage($destination);
    $final->destroy();
    $im->destroy();

}

function delete_files($dir) {
    if (substr($dir, strlen($dir) - 1, 1) != '/')
        $dir .= '/';
    if ($handle = opendir($dir)) {
        while ($obj = readdir($handle)) {
            if ($obj != '.' && $obj != '..') {
                if (is_dir($dir . $obj)) {
                    if (!delete_files($dir . $obj))
                        return false;
                }
                else if (is_file($dir . $obj)) {
                    if (!unlink($dir . $obj))
                        return false;
                }
            }
        }
        closedir($handle);
        if (!@rmdir($dir))
            return false;
        return true;
    }
    return false;
}

function zip2 ($source,$destination){

    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $relativeFilePath=$file;
            $file = realpath($file);

            if (is_dir($file) === true)
            {
                //$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
	        	//echo str_replace($source . '/', '', $file)."\n";
            }
            else if (is_file($file) === true)
            {
				//$zip->addFile(str_replace($source . '/', '', $file));
				$zip->addFile("/{$file}",str_replace($source, '/', $relativeFilePath));
				
            }
        }
    }
    else if (is_file($source) === true)
    {
		//$zip->addFile(basename($source));
				$zip->addFile(basename($source));
    }

    return $zip->close();
}

function Zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

//ini_set('memory_limit','512M');
mkdir("orders");
$uploadDestination="uploads/" .getNames(). "/temp_order/";
$dbh = connecttoDB();
$result=$dbh->prepare("select method from companyDB.methods");
$result->execute();
$methods=$result->fetchAll(PDO::FETCH_ASSOC);


$result=$dbh->prepare("select column_name from information_schema.columns
where table_schema = 'companyDB' and table_name='support_materials'
order by ordinal_position");
$result->execute();
$supportMatterials=$result->fetchAll(PDO::FETCH_ASSOC);


foreach($methods as $key=>$row){
	$result=$dbh->query("select column_name from information_schema.columns
where table_schema = 'companyDB' and table_name='".$row['method']."'
order by ordinal_position");
	$result->execute();
	$methods[$key]["columns"]=$result->fetchAll(PDO::FETCH_ASSOC);
}


//var_dump($supportMatterials);
//
//var_dump($result);
//var_dump($_POST['array']);
$paperCost=0;
$supportMatterialCost=0;
$protectionFilmCost=0;
$totalCost=0.0;

try{
	foreach($_POST['array'] as $key1=>$row1){
		if(is_array($row1)){
		$hasMethod=false;
			foreach($methods as $row){
				foreach($row1 as $key2=>$row2){
					if($key2==0){
						if($row2[1]["value"]==$row['method']){
							$hasMethod=true;
							$hasPaper=false;
							foreach($row['columns'] as $row3){
								if($row2[2]["value"] == $row3["column_name"]){
									$hasPaper=true;
									$result=$dbh->prepare("select ".$row['method'].".".$row3["column_name"]." from ".$row['method']." where dimensions=:dimension");
									$result->bindParam(":dimension", $row2[0]["value"]);
									$result->execute();
									$result=$result->fetchAll();
									$paperCost=floatval($result[0][0]);
									if($row2[4]["value"]=="none"){
										$supportMatterialCost=0;
									}
									else{
										$hasSupportMatterial=false;
										foreach($supportMatterials as $key4=>$row4){
											if($row4["column_name"]==$row2[4]["value"]){
												$result=$dbh->prepare("select support_materials.".$row4["column_name"]." from support_materials where dimensions=:dimension");
												$result->bindParam(":dimension", $row2[0]["value"]);
												$result->execute();
												$result=$result->fetchAll();
												$supportMatterialCost=floatval($result[0][0]);
												$hasSupportMatterial=true;
											}
										}
										if(!$hasSupportMatterial){
											throw new Exception("Invalid support_material value");
										}
									}
									if($row2[3]["value"]=="no"){
										$protectionFilmCost=0;
									}elseif ($row2[3]["value"]=="yes"){
										$result=$dbh->prepare("select protection_film.price from protection_film where dimensions=:dimension");
										$result->bindParam(":dimension", $row2[0]["value"]);
										$result->execute();
										$result=$result->fetchAll();
										$protectionFilmCost=floatval($result[0][0]);
									}else{
										throw new Exception("Invalid protection_film value");
									}
									$totalCost+=floatval($row2[5]["value"])*(count($row1)-1)*($supportMatterialCost + $paperCost +$protectionFilmCost);
								}
							}
							if(!$hasPaper){
								throw new Exception("Invalid paper value");
							}
						}
					}
				}
			}
		}
		if(!$hasMethod){
			throw new Exception("Invalid Method Value");
			$totalCost=0;
		}
	}
}catch(Exception $e){
	var_dump($e);
}
echo $totalCost;
foreach ( $_POST['array'] as $key=>$row){
    if(is_array($row)){
        $currentDir="";
        foreach($row as $key1=>$row1){
            if($key1 == 0){
                mkdir("orders"."/".getNames());
                mkdir("orders"."/".getNames()."/".$row1[1]["value"]);
                mkdir("orders"."/".getNames()."/".$row1[1]["value"]."/".$row1[0]["value"]);
                mkdir("orders"."/".getNames()."/".$row1[1]["value"]."/".$row1[0]["value"]."/".$row1[2]["value"]."_".$row1[3]["value"]."_".$row1[4]["value"]."_".$row1[5]["value"]."_".$key);
                $currentDir="orders"."/".getNames()."/".$row1[1]["value"]."/".$row1[0]["value"]."/".$row1[2]["value"]."_".$row1[3]["value"]."_".$row1[4]["value"]."_".$row1[5]["value"]."_".$key;
            }
            else{
                if(is_array($row1)){
                    edit($row1[1]["x"],$row1[1]["y"],$row1[1]["width"],$row1[1]["height"],$row1[1]["rotate"],$uploadDestination.$row1[0],$currentDir."/".$row1[0]);
                }
                else{
                    copy($uploadDestination.$row1,$currentDir."/".$row1);
                }
            }
        }
    }
}
$pathToStore=getFolderPath();
$orderCode;
$result=$dbh->prepare("select MAX(order_code) from companyDB.company_orders");
$result->execute();
$result=$result->fetchAll();
if(!isset($result[0]['MAX(order_code)'])){
    $zipArchive=$pathToStore."orders/0000000000_".getNames();
    $orderCode="00000000000";
}else{
    $orderCode=sprintf("%010d",(int)$result[0]['MAX(order_code)'] +1);
    $zipArchive=$pathToStore."orders/".$orderCode."_".getNames();
}

$result=$dbh->prepare("insert into companyDB.company_orders(total,for_user,folder_path) values (:total,:for_user,:folder_path)");
$result->bindParam(":total",$totalCost);
$result->bindParam(":for_user",$_SESSION['username']);
$result->bindParam(":folder_path",$zipArchive);
$result->execute();
//zip("orders/".getNames(),$zipArchive.'.zip');
zip2("orders/".getNames(),$zipArchive.'.zip');

$result=$dbh->prepare("SELECT * FROM company_orders,company_users WHERE company_orders.for_user=company_users.username AND company_orders.order_code=:order_code");
$result->bindParam("order_code",$orderCode);
$result->execute();
$result=$result->fetchAll();

$template=file_get_contents("email_templates/newOrder.html");
$template2=file_get_contents("email_templates/newOrderClient.html");

foreach($result[0] as $key=>$value){
    $template=str_replace('{{ '.$key.' }}',$value,$template);
    $template2=str_replace('{{ '.$key.' }}',$value,$template2);
}
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
$template=str_replace('{{ bar_code }}','<img src="data:image/png;base64,'.base64_encode($generator->getBarcode($orderCode, $generator::TYPE_CODE_128)).'" alt="">' ,$template);
$template=str_replace('{{ server_ip }}',$_SERVER['SERVER_ADDR'],$template);

$result=$dbh->prepare("SELECT email FROM company_users WHERE company_users.account_type='moderator' OR company_users.account_type='admin'");
$result->execute();
$result=$result->fetchAll();
ob_start();
foreach ($result as $key=>$value){
//    handleMail("New Order",$value['email'],$template);
}
//handleMail("New Order",getEmail(),$template2);
ob_clean();

delete_files("orders/".getNames());
