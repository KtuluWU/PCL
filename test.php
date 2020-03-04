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
$radio = $_GET["radio"];
$pg_pdo_conn_string = "pgsql:host=79.137.30.193;port=5432;dbname=DATAIFG_SCORE;user=infogreffe;password=3Mg0Fs2Eg2";
$pg_pdo_conn_string_afdcc = "pgsql:host=79.137.30.193;port=5432;dbname=DATAIFG_DW;user=infogreffe;password=3Mg0Fs2Eg2";
try {
    $db_pg_score = new PDO($pg_pdo_conn_string);
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}
try {
    $db_pg_score_afdcc = new PDO($pg_pdo_conn_string_afdcc);
} catch (PDOException $e) {
    die("Error!: " . $e->getMessage() . "<br/>");
}

function data_opendata($date1, $date2) {
    $authorization = "Authorization: apikey 767b3e8fe70a3df80dc5d36ee051777e541757b590cbaa2870185f7a";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( $authorization ));
    curl_setopt($ch, CURLOPT_URL, "https://opendata.datainfogreffe.fr/api/records/1.0/download/?dataset=pcl&q=date_ouverture_pcl>=\"$date1\"+and+date_ouverture_pcl<=\"$date2\"&fields=siren,date_ouverture_pcl");
    // curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

    $res = curl_exec($ch);

    curl_close($ch);

    return $res;
}


$res = data_opendata($date_1, $date_2);
$data_pre = preg_split('/[\r\n]+/s', $res);
$data = [];
for ($i = 1; $i < count($data_pre); $i++) {
    $data[$i-1] = explode(';', $data_pre[$i]);
}

$notes = array();
if ($radio == "score") {
    for ($i = 0; $i < count($data)-1; $i++) {
        $request_scoreifg = $db_pg_score->prepare("SELECT note FROM public.\"INFOGREFFE_scoreifg\" WHERE (siren='".$data[$i][0]."') AND (dttimestamp<='".$data[$i][1]."') ORDER BY dttimestamp DESC");
        $request_scoreifg->execute();
        $response = $request_scoreifg->fetch(PDO::FETCH_ASSOC);
        if ($response) {
            array_push($notes, $response);
        }
    }
} else if ($radio == "afdcc") {
    for ($i = 0; $i < count($data)-1; $i++) {
        $request_scoreifg = $db_pg_score_afdcc->prepare("SELECT noteafdcc FROM public.\"ta_scorafdcc_scoring_bil_new\" WHERE (siren='".$data[$i][0]."') AND (dttimestamp<='".$data[$i][1]."') ORDER BY dttimestamp DESC");
        $request_scoreifg->execute();
        $response = $request_scoreifg->fetch(PDO::FETCH_ASSOC);
        if ($response) {
            array_push($notes, $response);
        }
    }
}
var_dump($notes);
?>

<div id="container-notes" style="min-width: 310px; height: 400px; margin: 20px auto"></div>
<div class="return"><a href="./index.php">Retour</a></div>
</body>
<script src='assets/vendor/jquery-3.4.1.min.js'></script>
<script src='assets/js/action.js'></script>
</html>