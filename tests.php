<?php

require_once('vendor/autoload.php');
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
$base64string=$generator->getBarcode('00000000000', $generator::TYPE_CODE_128);
file_put_contents('img.png', $base64string);
echo '<a href="127.0.0.1/orders/0000000040_zafeiris_kipriotis_zk453a">127.0.0.1/orders/0000000040_zafeiris_kipriotis_zk453a</a>';
