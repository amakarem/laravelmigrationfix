<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$fn = fopen("migration.txt","r");

while(! feof($fn))  {
    $line = strstr(fgets($fn), '.php', true);
    $keyname = substr($line,0,17);
    $migrations[$keyname] = $line;
}
fclose($fn);
foreach ($migrations as $key => $value) {
    $sql = "select migration from migrations where substring(migration,1,17) = '$key' and migration <> '$value'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo $row['migration'] ." -> " .$value.PHP_EOL; 
        }
    }
    $sql = "update migrations set migration = '$value' where substring(migration,1,17) = '$key' and migration <> '$value'";
    $conn->query($sql);
}
