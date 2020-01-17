<?php

include_once("sessionHandler.php");

function compress($source, $destination, $quality) {
    $im=new Imagick($source);
    $thumbnail = $im->clone();
    $thumbnail->setImageCompression(Imagick::COMPRESSION_JPEG);
    $thumbnail->setImageCompressionQuality($quality);
    $thumbnail->stripImage();
    $thumbnail->writeImage($destination.".jpg");
    $thumbnail->destroy();
    $im->destroy();
}

function compressToThumbnail($source, $destination, $quality) {
    $im=new Imagick($source);
    $thumbnail = $im->clone();
    $thumbnail->setImageCompression(Imagick::COMPRESSION_JPEG);
    $thumbnail->setImageCompressionQuality($quality);
    $thumbnail->stripImage();
    $thumbnail->thumbnailImage(200,200,true,false);
    $thumbnail->writeImage($destination.".jpg");
    $thumbnail->destroy();
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


$output='';
if(is_array($_FILES)){
    $count = 0;
    $userDir="uploads/" .getNames();
    $uploadDestination="uploads/" .getNames(). "/temp_order/";
    $previewDestination="uploads/" .getNames(). "/temp_order/previews/";
    $thumbnailDestination="uploads/" .getNames(). "/temp_order/thumbnails/";

    if (!is_dir($uploadDestination)) {
        mkdir($userDir);
        mkdir($uploadDestination);
        mkdir($previewDestination);
        mkdir($thumbnailDestination);
    }else if(is_dir($uploadDestination)){
        delete_files($userDir);
        mkdir($userDir);
        mkdir($uploadDestination);
        mkdir($previewDestination);
        mkdir($thumbnailDestination);

    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        foreach ($_FILES['files']['name'] as $i => $name) {
            $imginfo=getimagesize($_FILES['files']['tmp_name'][$i]);
            if ($imginfo['mime']=='image/jpeg'
                || $imginfo['mime']=="image/gif"
                || $imginfo['mime']=="image/png"
                || $imginfo['mime']=="image/png"
                || $imginfo['mime']=="image/tiff"

            ){
                if (strlen($_FILES['files']['name'][$i]) > 1) {
                    if (move_uploaded_file($_FILES['files']['tmp_name'][$i], 'uploads/'.getNames()."/temp_order/".$name)) {
                        $tempDestination="uploads/".getNames()."/temp_order/thumbnails/".$name;
                        $tempDestination2="uploads/".getNames()."/temp_order/previews/".$name;
                        $previewDestination = substr($tempDestination, 0 , (strrpos($tempDestination, ".")));
                        $previewDestination2 = substr($tempDestination2, 0 , (strrpos($tempDestination2, ".")));
                        compressToThumbnail('uploads/'.getNames()."/temp_order/".$name,$previewDestination,0);
                        compress('uploads/'.getNames()."/temp_order/".$name,$previewDestination2,20);
                        echo "<div class=\"thumbnail\" id=\"".$name."\">
						<div class='imgDiv' style='background-image:url(\"".$previewDestination.".jpg\")' onmouseover='addToTempArray(this.parentNode.id)'onmousedown='addToTempArray(this.parentNode.id)'></div>
                      <div class='defaultText imgName'>".$name."</div>
                      </div>";
                        $count++;
                    }
                }
            }
        }
    }
    echo $output;

}
?>