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

$intervalle1 = array();
$intervalle2 = array();
$intervalle3 = array();
$intervalle4 = array();
$intervalle5 = array();
$intervalle6 = array();
$intervalle7 = array();
$intervalle8 = array();
$intervalle9 = array();
$intervalle10 = array();
$glb_intervalle = array();
$index_score = "note";
$index_afdcc = "noteafdcc";
$index = "";

foreach($notes as $note) {
    if ($radio == "afdcc") {
        $index = $index_afdcc;
        $note[$index] = $note[$index] * 5;
    } else {
        $index = $index_score;
    }
    switch($note) {
        case $note[$index] && $note[$index]>=0 && $note[$index] <= 10:
            array_push($intervalle1, $note[$index]);
            break;
        case $note[$index] && $note[$index]>=11 && $note[$index] <= 20:
            array_push($intervalle2, $note[$index]);
            break;
        case $note[$index] && $note[$index]>=21 && $note[$index] <= 30:
            array_push($intervalle3, $note[$index]);
            break;
        case $note[$index] && $note[$index]>=31 && $note[$index] <= 40:
            array_push($intervalle4, $note[$index]);
            break;
        case $note[$index] && $note[$index]>=41 && $note[$index] <= 50:
            array_push($intervalle5, $note[$index]);
            break;
        case $note[$index] && $note[$index]>=51 && $note[$index] <= 60:
            array_push($intervalle6, $note[$index]);
            break;
        case $note[$index] && $note[$index]>=61 && $note[$index] <= 70:
            array_push($intervalle7, $note[$index]);
            break;
        case $note[$index] && $note[$index]>=71 && $note[$index] <= 80:
            array_push($intervalle8, $note[$index]);
            break;  
        case $note[$index] && $note[$index]>=81 && $note[$index] <= 90:
            array_push($intervalle9, $note[$index]);
            break;
        case $note[$index] && $note[$index]>=91 && $note[$index] <= 100:
            array_push($intervalle10, $note[$index]);
            break; 
        default:
    }
}

array_push($glb_intervalle, count($intervalle1), count($intervalle2), count($intervalle3), count($intervalle4), count($intervalle5), count($intervalle6), count($intervalle7), count($intervalle8), count($intervalle9), count($intervalle10));

$sum = array_sum($glb_intervalle);

$pc1 = round((count($intervalle1)/$sum*100), 2);
$pc2 = round((count($intervalle2)/$sum*100), 2);
$pc3 = round((count($intervalle3)/$sum*100), 2);
$pc4 = round((count($intervalle4)/$sum*100), 2);
$pc5 = round((count($intervalle5)/$sum*100), 2);
$pc6 = round((count($intervalle6)/$sum*100), 2);
$pc7 = round((count($intervalle7)/$sum*100), 2);
$pc8 = round((count($intervalle8)/$sum*100), 2);
$pc9 = round((count($intervalle9)/$sum*100), 2);
$pc10 = round((count($intervalle10)/$sum*100), 2);
/*var_dump($intervalle1); 
echo "<br>下一个</br>";
var_dump($intervalle2);*/
?>

<div id="container-notes" style="min-width: 310px; height: 400px; margin: 20px auto"></div>
<div class="return"><a href="./index.php">Retour</a></div>
</body>
<script src='assets/vendor/jquery-3.4.1.min.js'></script>
<script src='assets/js/action.js'></script>
<script type="text/javascript">
Highcharts.chart('container-notes', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Les notes calculés de <?php echo $sum ?> PCL dans la période'
    },
    subtitle: {
        text: '<?php echo $date_1." ~ ".$date_2 ?>'
    },
    xAxis: {
        categories: [
            '0-10',
            '11-20',
            '21-30',
            '31-40',
            '41-50',
            '51-60',
            '61-70',
            '71-80',
            '81-90',
            '91-10',
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Nombres de PCL'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px;color:{series.color}">Nombres de PCL : </span><span style="font-size:10px"><b>{point.y}</b></span><br>',
        pointFormat: '<span style="color:{series.color};padding:0">Pourcentage: </span>' +
            '<span style="padding:0"><b>{point.name}</b></span>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Notes',
        data: [{
            name: "<?php echo $pc1."%" ?>" ,
            y: <?php echo count($intervalle1) ?>
        },{
            name: "<?php echo $pc2."%" ?>" ,
            y: <?php echo count($intervalle2) ?>
        },{
            name: "<?php echo $pc3."%" ?>" ,
            y: <?php echo count($intervalle3) ?>
        },{
            name: "<?php echo $pc4."%" ?>" ,
            y: <?php echo count($intervalle4) ?>
        },{
            name: "<?php echo $pc5."%" ?>" ,
            y: <?php echo count($intervalle5) ?>
        },{
            name: "<?php echo $pc6."%" ?>" ,
            y: <?php echo count($intervalle6) ?>
        },{
            name: "<?php echo $pc7."%" ?>" ,
            y: <?php echo count($intervalle7) ?>
        },{
            name: "<?php echo $pc8."%" ?>" ,
            y: <?php echo count($intervalle8) ?>
        },{
            name: "<?php echo $pc9."%" ?>" ,
            y: <?php echo count($intervalle9) ?>
        },{
            name: "<?php echo $pc10."%" ?>" ,
            y: <?php echo count($intervalle10) ?>
        }]
    }]
});
</script>
</html>