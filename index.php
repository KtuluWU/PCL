<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='assets/css/style.css'>
    <link rel='stylesheet' href='assets/css/sweet-alert.css'>
    <link rel='stylesheet' href='assets/css/dcalendar.picker.css'>
    <link rel='shortcut icon' href='assets/data_favicon.png' />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title> PCL </title>
</head>

<body>
    <div class="container">
        <div class="topbar">
            <div class="logo"></div>
            <div class="title">Monitoring Score IFG</div>
        </div>
        <div class="body">
            <form id="form_dates_saisies" name="form_dates_saisies" action="" method="POST">
                <div class="content">
                    <div class="content-title">Veuillez saisir les dates ouvertuers de début et de fin</div>
                    <div class="content-input">
                        <div class="content-prep">De</div>
                        <div>
                            <input placeholder="Année-Mois-Jour" name="date1" id="date1" autocomplete="off">
                            <label><i class="material-icons icon-calendar">today</i></label>
                        </div>
                        <div class="content-prep">à</div>
                        <div>
                            <input placeholder="Année-Mois-Jour" name="date2" id="date2" autocomplete="off">
                            <label><i class="material-icons icon-calendar">today</i></label>
                        </div>
                    </div>
                    <div class="content-button">
                        <input class="button" type="button" value="Envoyer" id="button" onclick="get_date_ouverture_pcl()">
                        <label><i class="material-icons icon-search">search</i></label>
                    </div>
                    <!--<div id="loading">
                        <img class="loading_gif" id="loading_gif" alt="Chargement..." src="assets/loading_gr.gif">
                    </div>-->
                </div>
            </form>
        </div>
    </div>
</body>
<script src='assets/vendor/jquery-3.4.1.min.js'></script>
<script src='assets/vendor/sweet-alert.js'></script>
<script src='assets/js/action.js'></script>
<script src="assets/vendor/dcalendar.picker.js"></script>
<script>
    $(document).ready(function () {
        $('#date1, #date2').dcalendarpicker({
            format: 'yyyy-mm-dd'
        });
    });
</script>
</html>