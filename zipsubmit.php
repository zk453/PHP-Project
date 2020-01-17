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

ini_set('max_execution_time','300');

$output='';
if(isset($_FILES)) {
    $count = 0;
    $userDir="uploads/" .getNames();
    $uploadDestination="uploads/" .getNames(). "/temp_order/";
    $previewDestination="uploads/" .getNames(). "/temp_order/previews/";
    $thumbnailDestination="uploads/" .getNames(). "/temp_order/thumbnails/";

    if (!is_dir($userDir)) {
        mkdir($userDir);
        mkdir($uploadDestination);
        mkdir($previewDestination);
        mkdir($thumbnailDestination);
    }else{
        delete_files($userDir);
        mkdir($userDir);
        mkdir($uploadDestination);
        mkdir($previewDestination);
        mkdir($thumbnailDestination);

    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $zipArchive = new ZipArchive();
        $result=$zipArchive->open($_FILES['file']['tmp_name']);
        if($result===true) {
            $zipArchive->extractTo($uploadDestination);
            $zipArchive->close();
        }else{
            echo 'error uploading file';
        }
        foreach(glob($uploadDestination."*.{jpg,png,tiff}",GLOB_BRACE ) as $file){

            $tempDestination=$thumbnailDestination.basename($file);
            $tempDestination2=$previewDestination.basename($file);
            $srcDestination = substr($tempDestination, 0 , (strrpos($tempDestination, ".")));
            $srcDestination2 = substr($tempDestination2, 0 , (strrpos($tempDestination2, ".")));
            compressToThumbnail($file,$srcDestination,0);
            compress($file,$srcDestination2,20);
					echo "<div class=\"thumbnail\" id=\"".basename($file)."\" >
							<div class='imgDiv' style='background-image:url(\"".$srcDestination.".jpg\")' onmouseover='addToTempArray(this.parentNode.id)' onmousedown='addToTempArray(this.parentNode.id)'></div>
						  <div class='defaultText imgName'>".basename($file)."</div>
							</div>";
        }
    }
}
