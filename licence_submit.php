
<?php
include 'common.php';
include 'logger.php';

$user = $_SESSION['username'];
$product = $_POST['product'];
$serial_number = $_POST['serial_number'];
$gpon_ports = $_POST['gpon_ports'];
$xgs_ports = $_POST['xgs_ports'];

$dbh = OpenCon();
$testUser = false;
$valid_until = "Unlimited";
if ($user == 'test') {
    $valid_until = calculateExpirationDate();
}



function updateIfExists($serial_number, $new_ports)
{
    global $dbh;
    $sql = 'SELECT * FROM licence_data WHERE serial_number=:number';
    $statement = $dbh->prepare($sql);
    $statement->execute([':number' => $serial_number]);
    $result = $statement->fetch(PDO::FETCH_OBJ);
    $existing_ports = $result->xgs_ports;
    if ($existing_ports == null) return false;
    if ($existing_ports >= $new_ports) {
        header("Location: error.php?msg=" . 'Licence number ' . $serial_number . ' already uses ' . $existing_ports . ' XGS Ports. Ports you add has to exceed that number!');
        return true;
    } else {
        updateLicence($serial_number, $new_ports, $existing_ports);
        return true;
    }
}

function updateLicence($serial_number, $new_ports, $existing_ports)
{
    global $dbh;
    $sql = 'UPDATE licence_data SET xgs_ports=:xgs_ports WHERE serial_number=:number';
    $statement = $dbh->prepare($sql);
    $statement->execute([':number' => $serial_number, ':xgs_ports' => $new_ports]);
    logEvent($new_ports - $existing_ports, $serial_number);
}

//Inserts licence data in database with data from the "licform" form in licence_retrieve.php
function insertLicence($product, $user, $serial_number, $gpon_ports, $xgs_ports, $valid_until)
{
    global $dbh;
    global $product;
    global $serial_number;
    global $gpon_ports;
    global $xgs_ports;
    $user = $_SESSION['username'];

    if (updateIfExists($serial_number, $xgs_ports)) return;

    $query = "INSERT INTO licence_data(product, user, serial_number, gpon_ports, xgs_ports, valid_until) VALUES(:product, :user, :serial_number, :gpon_ports, :xgs_ports, :valid_until)";
    $callToDb = $dbh->prepare($query);
    $callToDb->bindParam(":product", $product);
    $callToDb->bindParam(":serial_number", $serial_number);
    $callToDb->bindParam(":gpon_ports", $gpon_ports);
    $callToDb->bindParam(":xgs_ports", $xgs_ports);
    $callToDb->bindParam(":user", $user);
    $callToDb->bindParam(":valid_until", $valid_until);

    try {
        $callToDb->execute();
        logEvent($xgs_ports, $serial_number);
    } catch (Exception $e) {
        header("Location: error.php?msg=" . $e->getMessage());
    }
}

//Downloads the file from the specified URL in the browser
function downloadFile($url)
{
    if (file_exists($url)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($url));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($url));
        ob_clean();
        flush();
        readfile($url);
        exit;
    }
}

//Executes the script with the provided attributes
function generateLicence($product, $serial_number, $gpon_ports, $xgs_ports, $valid_until)
{
    global $dbh;
    $sql = 'SELECT * FROM licence_data WHERE serial_number=:number';
    $statement = $dbh->prepare($sql);
    $statement->execute([':number' => $serial_number]);
    $data = $statement->fetch(PDO::FETCH_OBJ);
    chdir('/opt/licence/licences/');
    if($valid_until == "Unlimited"){
        $valid_until = "unlimit";
    }else{
        $valid_until = 30;
    }
    shell_exec(sprintf('/opt/licence/OLT_create_lic "%s" "%s" "%s" "%s" "%s" "%s"', $data->licence_id, $product, $serial_number, $gpon_ports, $xgs_ports, $valid_until));
}

if (isset($product)) {
    $result = insertLicence($product, $user, $serial_number, $gpon_ports, $xgs_ports, $valid_until);
    $result = generateLicence($product, $serial_number, $gpon_ports, $xgs_ports, $valid_until);
    header("Refresh:0; url=index.php");
}
// $result = downloadFile($url);

exit();
