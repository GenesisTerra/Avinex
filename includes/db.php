<?php
$servername = "db.fr-pari1.bengt.wasmernet.com";
$username = "c5fe5b077ab88000829f849ea1b3";
$password = "0688c5fe-5b07-7bf3-8000-f3f9a4b49950";
$dbname = "webapp";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
