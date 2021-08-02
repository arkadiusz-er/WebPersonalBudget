function displayRegistrationPanel() {
    $('.panel-login').css("display", "none");
    $('.panel-registration').css("display", "block");
    $('#login-option').removeClass("home-active-option");
    $('#login-option').addClass("home-noactive-option");
    $('#registration-option').removeClass("home-noactive-option");
    $('#registration-option').addClass("home-active-option");
}

function displayLoginPanel() {
    $('.panel-login').css("display", "block");
    $('.panel-registration').css("display", "none");
    $('#login-option').removeClass("home-noactive-option");
    $('#login-option').addClass("home-active-option");
    $('#registration-option').removeClass("home-active-option");
    $('#registration-option').addClass("home-noactive-option");
}

$('#login-option').on("click", displayLoginPanel);
$('#registration-option').on("click", displayRegistrationPanel);

var today = new Date();
var day = today.getDate();
var month = today.getMonth() + 1;
var year = today.getFullYear();
if (month < 10) month = "0" + month;
if (day < 10) day = "0" + day;
var maxDate = year + '-' + month + '-' + day;
$('#dateID').attr('max', maxDate);
$('#dateID').val(maxDate);

var heightDivTableResults = parseFloat($('.div-table-results:last-child').css('height'));
$('.div-table-results:first-child').css('height', heightDivTableResults);
$('.article-results').css('min-height', heightDivTableResults);

google.load("visualization", "1", { packages: ["corechart"] });
google.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['category', 'amount'],
        ['Mieszkanie', 1200],
        ['Jedzenie', 500],
        ['Ubrania', 200],
        ['Rozrywka', 300]
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


