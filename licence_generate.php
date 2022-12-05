<?php
$licence_id = $_GET['licence_id'];
$product = $_GET['product'];
$serial_number = $_GET['serial_number'];
$gpon_ports = $_GET['gpon_ports'];
$xgs_ports = $_GET['xgs_ports'];
$valid_until = $_GET['valid_until'];
$valid_until = str_replace("/","-",$valid_until);



$url = "/opt/licence/licences/OLT_licence-" . $product . "-" . $serial_number . "-" . $gpon_ports . "-" . $xgs_ports . "-" . $valid_until . ".lic";


header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($url).'"');
header('Content-Length: ' . filesize($url));
header('Pragma: public');

readfile($url);
?>