<?php 
error_reporting(E_ALL || ~E_NOTICE);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='assets/css/style.css'>
    <link rel='shortcut icon' href='assets/data_favicon.png' />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title> PCL-Notes </title>
</head>
<body>
<script src='assets/vendor/highcharts/code/highcharts.js'></script>
<script src='assets/vendor/highcharts/code/modules/exporting.js'></script>
<script src='assets/vendor/highcharts/code/modules/export-data.js'></script>
<script src='assets/vendor/highcharts/code/themes/sand-signika.js'></script>
    
<?php
$date_1 = $_GET["date1"];
$date_2 = $_GET["date2"];
$pg_pdo_conn_string = "pgsql:host=79.137.30.193;port=5432;dbname=DATAIFG_SCORE;user=infogreffe;password=3Mg0Fs2Eg2";
try {
    $db_pg_score = new PDO($pg_pdo_conn_string);
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
echo $date_1;