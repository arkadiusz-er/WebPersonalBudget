
//Wyciąganie daty
var today = new Date();
var day = today.getDate();
var month = today.getMonth() + 1;
var year = today.getFullYear();
if (month < 10) month = "0" + month;
if (day < 10) day = "0" + day;
var maxDate = year + '-' + month + '-' + day;


//skrócona wersja
if(document.getElementById("dateIncExp")) {
	document.getElementById("dateIncExp").value = maxDate;
	document.getElementById("dateIncExp").max = maxDate;
}
if(document.getElementById("dateStartForm")) {
	document.getElementById("dateStartForm").max = maxDate;
	document.getElementById("dateEndForm").min = document.getElementById("dateStartForm").value;
}
if(document.getElementById("dateEndForm")) document.getElementById("dateEndForm").max = maxDate;

/*
//wydłużona wersja
var dateIncomeExpense = document.getElementById("dateIncExp");
dateIncomeExpense.setAttribute("value", maxDate);
dateIncomeExpense.setAttribute("max", maxDate);

var dateStart = document.getElementById("dateStartForm");
dateStart.setAttribute("max", maxDate);

var dateEnd = document.getElementById("dateEndForm");
dateEnd.setAttribute("max", maxDate);
*/
/*
var heightDivTableResults = parseFloat($('.div-table-results:last-child').css('height'));
$('.div-table-results:first-child').css('height', heightDivTableResults);
$('.article-results').css('min-height', heightDivTableResults);
*/

google.load("visualization", "1", { packages: ["corechart"] });
google.setOnLoadCallback(drawChart);


function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['category', 'amount'],
		<?php
			session_start();
			if (isset($_SESSION['expenses_vis_string'])) echo $_SESSION['expenses_vis_string'];
		?>
    ]);

    var options = {
        title: 'Moje wydatki'
    };

    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}

$(window).on("throttledresize", function (event) {
    var options = {
        width: '100%',
        height: '100%'
    };

    var data = google.visualization.arrayToDataTable([]);
    drawChart(data, options);
});


