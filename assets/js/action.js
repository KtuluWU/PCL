console.log("Auteur: Yun WU");

function get_date_ouverture_pcl() {
    var date_1 = document.form_dates_saisies.date1.value;
    var date_2 = document.form_dates_saisies.date2.value;

    if (date_check(date_1, date_2)) {
        window.location.href = "./action.php?date1="+date_1+"&date2="+date_2;
    }
}

function date_check(date1, date2) {
    if (!date1) {
        swal({
            title: "Échoué!",
            text: "La date de début ("+date1+") ne doit pas être vide !",
            type: "error" 
        })
        return false;
    } else if (!date2) {
        swal({
            title: "Échoué!",
            text: "La date de fin ("+date2+") ne doit pas être vide !",
            type: "error" 
        })
        return false;
    } else if (date1 > date2) {
        swal({
            title: "Échoué!",
            text: "La date de début ("+date1+") ne doit pas être supérieur à la date de fin ("+date2+") !",
            type: "error" 
        })
        return false;
    } else if (date1 < "2018-06-07") {
        swal({
            title: "Échoué!",
            text: "La date de début ("+date1+") doit être supérieur à la date 2018-06-07 !",
            type: "error" 
        })
        return false;
    } else {
        return true;
    }
}