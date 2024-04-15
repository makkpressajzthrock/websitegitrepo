<?php
require_once 'HunterObfuscator.php';

$hunter1 = new HunterObfuscator($script_1);
$script_1 = $hunter1->Obfuscate();

$hunter2 = new HunterObfuscator($script_2);
$script_2 = $hunter2->Obfuscate();

$hunter3 = new HunterObfuscator($script_3);
$script_3 = $hunter3->Obfuscate();

?>