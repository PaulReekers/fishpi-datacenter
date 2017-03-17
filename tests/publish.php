<?php 

include '../app/Extensions/phpMQTT.php';
use App\Extensions\phpMQTT;

$mqtt = new phpMQTT('95.85.5.39', '1883', 'asakskjaksj');

if ($mqtt->connect()) {
    $mqtt->publish("fishpi/hello", "Hello! World");
    echo "Message Published\n";
    $mqtt->close();
} else {
    throw new Exception('Error', 500);
}
